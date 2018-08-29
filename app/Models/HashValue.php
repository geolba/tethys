<?php

namespace App\Models;

use App\Models\File;
use Illuminate\Database\Eloquent\Model;

class HashValue extends Model
{
    protected $table = 'file_hashvalues';
    public $timestamps = false;

    public function file()
    {
        return $this->belongsTo(File::class, 'file_id', 'id');
    }
}
