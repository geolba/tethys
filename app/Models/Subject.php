<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dataset;

class Subject extends Model
{
    protected $table = 'document_subjects';
    public $timestamps = false;

    protected $fillable = ['value', 'type', 'language'];

    public function dataset()
    {
        return $this->belongsTo(Dataset::class, 'document_id', 'id');
    }
}
