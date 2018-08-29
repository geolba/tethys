<?php
namespace App\Models;

//use App\Library\Xml\DatasetExtension;

use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    protected $table = 'document_title_abstracts';
    public $timestamps = false;
    

    protected $fillable = [
    ];
    
    public function dataset()
    {
        return $this->belongsTo(\App\Dataset::class, 'document_id', 'id');
    }
}
