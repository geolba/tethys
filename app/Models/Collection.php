<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dataset;

class Collection extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name',
        'role_id',
    ];

    public function documents()
    {
        return $this->belongsToMany(Dataset::class, 'link_documents_collections', 'collection_id', 'document_id');
    }

    #region self join
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
    #endregion

    /**
     * Get the collection role that the dataset belongs to.
     */
    public function collectionrole()
    {
        return $this->belongsTo(CollectionRole::class, 'role_id', 'id');
    }
}
