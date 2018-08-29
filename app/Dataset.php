<?php

namespace App;

use App\Library\Xml\DatasetExtension;
use Illuminate\Database\Eloquent\Model;
use App\Models\Title;

class Dataset extends Model
{
    use DatasetExtension;
    protected $table = 'documents';

    //public $timestamps = false; //default true
    // customize the names of the columns used to store the timestamps:
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'server_date_modified';
    const PUBLISHED_AT = 'server_date_published';

    protected $fillable = [
        'type',
        'server_state',
        'creating_corporation',
        'project_id',
        'embargo_date',
        'belongs_to_bibliography'
    ];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'server_date_created',
        'server_date_modified',
        'server_date_published',
    ];
    //protected $dateFormat = 'Y-m-d';

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        // $this->_init();
    }

    /**
     * Get the project that the product belongs to.
     */
    public function project()
    {
        return $this->belongsTo(\App\Project::class, 'project_id', 'id');
    }

    public function collections()
    {
        return $this
            ->belongsToMany(\App\Collection::class, 'link_documents_collections', 'document_id', 'collection_id');
    }

    #region [person table]

    //return all persons attached to this film
    public function persons()
    {
        return $this->belongsToMany(\App\Person::class, 'link_documents_persons', 'document_id', 'person_id')
            ->withPivot('role');
    }

    /**
     * Return all authors for this dataset
     *
     * @return \App\Person
     */
    public function authors()
    {
        return $this
            ->belongsToMany(\App\Person::class, 'link_documents_persons', 'document_id', 'person_id')
            ->wherePivot('role', 'author');
    }

    /**
     * Add author to dataset
     *
     * @param \App\User $user user to add
     *
     * @return void
     */
    public function addAuthor(\App\User $user): void
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
            ->belongsToMany(\App\Person::class, 'link_documents_persons', 'document_id', 'person_id')
            ->wherePivot('role', 'contributor');
    }

    #endregion

    #region title table:
    public function titlesAbstracts()
    {
        return $this->hasMany(Title::class, 'document_id', 'id');
    }

    public function titles()
    {
        return $this->hasMany(Title::class, 'document_id', 'id')
            ->where('type', 'main');
    }
    public function addMainTitle(Title $title)
    {
        $title->type = 'main';
        $this->titlesAbstracts()->save($title);
        // $this->titles()->save($title, ['type' => 'main']);
    }

    /**
     * Relation abstracts
     *
     * @return \App\Title
     */
    public function abstracts()
    {
        return $this->hasMany(Title::class, 'document_id', 'id')
            ->where('type', 'abstract');
    }
    public function addMainAbstract(Title $title)
    {
        $title->type = 'abstract';
        $this->titlesAbstracts()->save($title);
        // $this->abstracts()->save($title, ['type' => 'abstract']);
    }

    #endregion title table

    public function licenses()
    {
        return $this->belongsToMany(\App\License::class, 'link_documents_licences', 'document_id', 'licence_id');
    }

    public function files()
    {
        return $this->hasMany(\App\Models\File::class, 'document_id', 'id');
    }

    /**
     * Get the xml-cache record associated with the dataset.
     *
     * @return \App\XmlCache
     */
    public function xmlCache()
    {
        return $this->hasOne(\App\XmlCache::class, 'document_id', 'id');
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
    public function scopeEarliestPublicationDate($query, string $column = null)
    {
        if (!$column) {
            $column = self::PUBLISHED_AT;
        }
        return $query->whereNotNull('server_date_published')
            ->where('server_state', 'published')
            ->orderBy('server_date_published', 'asc')
            ->first()
            ->server_date_published;
    }

    public function hasProject()
    {
        return $this->project()->exists();
    }
}
