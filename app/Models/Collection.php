<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dataset;

class Collection extends Model
{

    public function documents()
    {
        return $this->belongsToMany(Dataset::class, 'link_documents_collections', 'collection_id', 'document_id');
    }
}
