<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    protected $table = 'document_licences';
    public $timestamps = false;

    protected $fillable = [
        'active',
        'desc_text',
        'desc_text',
        'desc_text',
        'language',
        'link_licence',
        'link_logo',
        'mime_type',
        'name_long',
        'pod_allowed',
        'sort_order'
    ];

    public function datasets()
    {
        return $this->belongsToMany(\App\Dataset::class, 'link_documents_licences', 'licence_id', 'document_id');
    }
}
