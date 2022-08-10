<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Library\Xml\DatasetExtension;
use App\Models\Collection;
use App\Models\Coverage;
use App\Models\Description;
use App\Models\File;
use App\Models\License;
use App\Models\Person;
use App\Models\Project;
use App\Models\Title;
use App\Models\User;
use App\Models\XmlCache;
use App\Models\DatasetIdentifier;
use Carbon\Carbon;
// use App\Models\GeolocationBox;
use Illuminate\Database\Eloquent\Model;

class Dataset extends Model
{
    use DatasetExtension, HasFactory;
    protected $table = 'documents';

    //public $timestamps = false; //default true
    // customize the names of the columns used to store the timestamps:
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'server_date_modified';
    const PUBLISHED_AT = 'server_date_published';

    protected $fillable = [
        'type',
        'language',
        'server_state',
        'server_date_published',
        'publisher_name',
        'publish_id',
        'creating_corporation',
        'project_id',
        'embargo_date',
        'belongs_to_bibliography',
        'editor_id',
        'preferred_reviewer',
        'preferred_reviewer_email',
        'reviewer_id',
        'reject_reviewer_note',
        'reject_editor_note',
        'reviewer_note_visible',
    ];
    //protected $guarded = [];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'server_date_created',
        'server_date_modified',
        'server_date_published',
        'embargo_date',
    ];
    //protected $dateFormat = 'Y-m-d';

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        // $this->_init();
    }

    // public function setUpdatedAt($value)
    // {
    //     $this->{static::UPDATED_AT} = $value;
    // }

    /**
     * Get the geolocation that owns the dataset.
     */
    // public function geolocation()
    // {
    //     return $this->hasOne(GeolocationBox::class, 'dataset_id', 'id');
    // }

    /**
     * Get the coverage that owns the dataset.
     */
    public function coverage()
    {
        return $this->hasOne(Coverage::class, 'dataset_id', 'id');
    }

    /**
     * Get the project that the dataset belongs to.
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

     /**
     * Get the doi indentifier that owns the dataset.
     */
    public function identifier()
    {
        return $this->hasOne(DatasetIdentifier::class, 'dataset_id', 'id');
    }

    /**
     * Get the account that the dataset belongs to
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'account_id', 'id');
    }

    /**
     * Get the editor of the dataset
     */
    public function editor()
    {
        return $this->belongsTo(User::class, 'editor_id', 'id');
    }

    /**
     * Get the editor of the dataset
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id', 'id');
    }

    public function collections()
    {
        return $this
            ->belongsToMany(Collection::class, 'link_documents_collections', 'document_id', 'collection_id');
    }

    // public function collectionRoles()
    // {
    //     return $this
    //         ->belongsToMany(CollectionRole::class, 'link_documents_collections', 'document_id', 'role_id');
    // }

    #region [person table]

    //return all persons attached to this film
    public function persons()
    {
        return $this
            ->belongsToMany(Person::class, 'link_documents_persons', 'document_id', 'person_id')
            ->withPivot('role', 'sort_order', 'allow_email_contact', 'contributor_type');
    }

    /**
     * Return all authors for this dataset
     *
     * @return \App\Person
     */
    public function authors()
    {
        return $this
            ->persons()
            //->belongsToMany(Person::class, 'link_documents_persons', 'document_id', 'person_id')
            ->wherePivot('role', 'author')
            ->orderBy('link_documents_persons.sort_order');
    }

    /**
     * Add author to dataset
     *
     * @param Person $user user to add
     *
     * @return void
     */
    public function addAuthor(Person $user): void
    {
        $this->persons()->save($user, ['role' => 'author']);
    }

    /**
     * Return all contributors for this dataset
     *
     * @return \App\Person
     */
    public function contributors()
    {
        return $this
            ->persons()
            // ->belongsToMany(Person::class, 'link_documents_persons', 'document_id', 'person_id')
            ->wherePivot('role', 'contributor')
            ->orderBy('link_documents_persons.sort_order');
    }

    #endregion

    #region title table:
    public function titles()
    {
        return $this->hasMany(Title::class, 'document_id', 'id');
    }
    public function additionalTitles()
    {
        return $this->hasMany(Title::class, 'document_id', 'id')->where('type', '!=', 'Main');
    }

    public function mainTitle()
    {
        return $this->hasMany(Title::class, 'document_id', 'id')->where('type', 'Main')->first();
    }

    public function addMainTitle(Title $title)
    {
        $title->type = 'main';
        $this->titles()->save($title);
        // $this->titles()->save($title, ['type' => 'main']);
    }

    /**
     * Relation abstracts
     *
     * @return \App\Description
     */
    public function abstracts()
    {
        return $this->hasMany(Description::class, 'document_id', 'id');
    }
    public function additionalAbstracts()
    {
        return $this->hasMany(Description::class, 'document_id', 'id')->where('type', '!=', 'Abstract');
    }
    public function mainAbstract()
    {
        return $this->hasMany(Description::class, 'document_id', 'id')->where('type', 'Abstract')->first();
    }
    public function addMainAbstract(Description $title)
    {
        $title->type = 'abstract';
        $this->abstracts()->save($title);
        // $this->abstracts()->save($title, ['type' => 'abstract']);
    }

    #endregion title table

    public function licenses()
    {
        return $this->belongsToMany(License::class, 'link_documents_licences', 'document_id', 'licence_id');
    }
    public function license()
    {
        return $this->belongsToMany(License::class, 'link_documents_licences', 'document_id', 'licence_id')->first();
    }

    public function files()
    {
        return $this->hasMany(File::class, 'document_id', 'id');
    }

    public function references()
    {
        return $this->hasMany(\App\Models\DatasetReference::class, 'document_id', 'id');
    }

    // public function subjects()
    // {
    //     return $this->hasMany(\App\Models\Subject::class, 'document_id', 'id');
    // }
    public function subjects()
    {
        return $this->belongsToMany(\App\Models\Subject::class, 'link_dataset_subjects', 'document_id', 'subject_id');
    }

    /**
     * Get the xml-cache record associated with the dataset.
     *
     * @return \App\Models\XmlCache
     */
    public function xmlCache()
    {
        return $this->hasOne(XmlCache::class, 'document_id', 'id');
    }

    public function scopeOrderByType($query)
    {
        return $query->orderBy('type');
    }

    /**
     * Get earliest publication date.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query  sql-query
     * @param string                                $column column
     *
     * @return \Carbon\Carbon\Date
     */
    public static function earliestPublicationDate(string $column = null)
    {
        if (!$column) {
            $column = self::PUBLISHED_AT;
        }
        $result = Dataset::select('server_date_published')
            ->where('server_date_published', '<>', null)
            ->where('server_state', 'published')
            ->orderBy('server_date_published', 'asc')
            ->first();
        //->server_date_published;
        return $result;
    }

    public function setServerState($targetType)
    {
        $this->attributes['server_state'] = $targetType;
        //$this->server_state = $targetType;
    }

    public function hasProject()
    {
        return $this->project()->exists();
    }

    public function hasEmbargoPassed($now = null)
    {
        $embargoDate = $this->embargo_date;
        if (is_null($embargoDate)) {
            return true;
        }

        if (is_null($now)) {
            $now = $dt = Carbon::now();
        }
        // Embargo has passed on the day after the specified date
        // $embargoDate->setHour(23);
        // $embargoDate->setMinute(59);
        // $embargoDate->setSecond(59);
        // $embargoDate->setTimezone('Z');

        // $dt->year   = 2015;
        // $dt->month  = 04;
        // $dt->day    = 21;
        $embargoDate->hour = 23;
        $embargoDate->minute = 59;
        $embargoDate->second = 59;

        return ($embargoDate->lessThan($now));
        //return ($embargoDate->gt($now) == true);
    }

    public function getRemainingTimeAttribute()
    {
        $dateDiff = $this->server_date_modified->addDays(14);
        if ($this->server_state == "approved") {
            return Carbon::now()->diffInDays($dateDiff, false);
        } else {
            return 0;
        }
    }

    public function geoLocation()
    {
        // return $this->coverage->x_min;
        $geolocation =
        'SOUTH-BOUND LATITUDE: ' . $this->coverage->x_min . ","
        . ' * WEST-BOUND LONGITUDE: ' . $this->coverage->y_min . ","
        . ' * NORTH-BOUND LATITUDE: ' . $this->coverage->x_max . ","
        . ' * EAST-BOUND LONGITUDE: ' . $this->coverage->y_max;

        $elevation = '';
        if ($this->coverage->elevation_min != null && $this->coverage->elevation_max != null) {
            $elevation = $elevation . '* ELEVATION MIN: ' . $this->coverage->elevation_min
            . ', * ELEVATION MAX: ' . $this->coverage->elevation_max;
        } elseif ($this->coverage->elevation_absolut != null) {
            $elevation = $elevation . '* ELEVATION ABSOLUT: ' . $this->coverage->elevation_absolut;
        }
        $geolocation = $elevation == '' ? $geolocation : $geolocation . ", " . $elevation;

        $depth = '';
        if ($this->coverage->depth_min != null && $this->coverage->depth_max != null) {
            $depth = $depth . '* DEPTH MIN: ' . $this->coverage->depth_min
            . ', * DEPTH MAX: ' . $this->coverage->depth_max;
        } elseif ($this->coverage->depth_absolut != null) {
            $depth = $depth . '* DEPTH ABSOLUT: ' . $this->coverage->depth_absolut;
        }
        $geolocation = $depth == '' ? $geolocation : $geolocation . ", " . $depth;

        $time = '';
        if ($this->coverage->time_min != null && $this->coverage->time_max != null) {
            $time = $time . '* TIME MIN: ' . $this->coverage->time_min
            . ', * TIME MAX: ' . $this->coverage->time_max;
        } elseif ($this->coverage->time_absolut != null) {
            $time = $time . '* TIME ABSOLUT: ' . $this->coverage->time_absolut;
        }
        $geolocation = $time == '' ? $geolocation : $geolocation . ", " . $time;

        return $geolocation;
    }
}
