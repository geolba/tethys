<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    
    public function documents()
    {
        return $this->belongsToMany(\App\Dataset::class, 'link_documents_collections', 'collection_id', 'document_id');
    }
}
