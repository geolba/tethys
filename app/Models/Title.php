<?php
namespace App\Models;

//use App\Library\Xml\DatasetExtension;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dataset;

class Title extends Model
{
    protected $table = 'document_title_abstracts';
    public $timestamps = false;
    

    protected $fillable = [
    ];
    
    public function dataset()
    {
        return $this->belongsTo(Dataset::class, 'document_id', 'id');
    }
}
