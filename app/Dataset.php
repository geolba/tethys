<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Library\Xml\DatasetExtension;
use phpDocumentor\Reflection\Types\Null_;

class Dataset extends Model
{
    use DatasetExtension;
    protected $table = 'documents';
    
    //public $timestamps = false; //default true
    // customize the names of the columns used to store the timestamps:
    const CREATED_AT = 'server_date_created';
    const UPDATED_AT = 'server_date_modified';
    const PUBLISHED_AT = 'server_date_published';

    protected $fillable = [
        'type',
        'publication_state',
        'thesis_year_accepted',
        'project_id',
        'embargo_date'
    ];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'server_date_created',
        'server_date_modified',
        'server_date_published'
    ];
    //protected $dateFormat = 'Y-m-d';


    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        // $this->_init();
    }

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
    public function addAuthor(\App\User $user) : void
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

    public function titles()
    {
        return $this->hasMany(\App\Title::class, 'document_id', 'id')
            ->where('type', 'main');
    }
    // public function addTitle(\App\Title $title)
    // {
    //     $this->persons()->save($title, ['type' => 'main']);
    // }

    /**
     * Relation abstracts
     *
     * @return \App\Title
     */
    public function abstracts()
    {
        return $this->hasMany(\App\Title::class, 'document_id', 'id')
            ->where('type', 'abstract');
    }
    // public function addAbstract (\App\Title $title)
    // {
    //     $this->persons()->save($title, ['type' => 'abstract']);
    // }

    #endregion

    public function licenses()
    {
        return $this->belongsToMany(\App\License::class, 'link_documents_licences', 'document_id', 'licence_id');
    }

    public function files()
    {
        return $this->hasMany(\App\File::class, 'document_id', 'id');
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
