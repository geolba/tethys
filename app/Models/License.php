<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dataset;

class License extends Model
{
    protected $table = 'document_licences';
    public $timestamps = false;

    protected $fillable = [
        'name_long',
        'language',
        'link_licence',
        'link_logo',
        'desc_text',
        'desc_markup',
        'comment_internal',
        'mime_type',
        'sort_order',
        'active',
        'pod_allowed'
    ];

    public function datasets()
    {
        return $this->belongsToMany(Dataset::class, 'link_documents_licences', 'licence_id', 'document_id');
    }
}
