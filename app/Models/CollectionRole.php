<?php

namespace App\Models;

use App\Models\Collection;
// use App\Models\Dataset;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CollectionRole extends Model
{
    use HasFactory;
    
    protected $table = 'collections_roles';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'oai_name',
        'visible',
    ];

    // public function documents()
    // {
    //     return $this->belongsToMany(Dataset::class, 'link_documents_collections', 'role_id', 'document_id');
    // }

    public function collections()
    {
        //model, foreign key on the Collection model is role_id, local id of this is id
        return $this->hasMany(Collection::class, 'role_id', 'id');
    }
}
