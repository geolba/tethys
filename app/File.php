<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'document_files';
    public $timestamps = false;
    

    protected $fillable = [];
    
    public function dataset()
    {
        return $this->belongsTo(\App\Dataset::class, 'document_id', 'id');
    }
}
