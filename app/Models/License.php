<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dataset;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class License extends Model
{
    use HasFactory;
    protected $table = 'document_licences';
    public $timestamps = false;

    protected $fillable = [
        'name_long',
        'name',
        'language',
        'link_licence',
        'link_logo',
        'desc_text',
        'desc_markup',
        'comment_internal',
        'mime_type',
        'sort_order',
        'language',
        'active',
        'pod_allowed'
    ];

     // See the array called $touches? This is where you put all the relationships you want to get
     // updated_at as soon as this Model is updated
     protected $touches = ['datasets'];

    public function datasets()
    {
        return $this->belongsToMany(Dataset::class, 'link_documents_licences', 'licence_id', 'document_id');
    }

    public function getCheckedAttribute()
    {
        return "false";
    }
}
