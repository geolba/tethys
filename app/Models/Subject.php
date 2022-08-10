<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dataset;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    Use HasFactory;
    // protected $table = 'document_subjects';
    protected $table = 'dataset_subjects';
    public $timestamps = false;

    protected $fillable = ['value', 'type', 'language'];

    // public function dataset()
    // {
    //     return $this->belongsTo(Dataset::class, 'document_id', 'id');
    // }
    public function datasets()
    {
        return $this->belongsToMany(Dataset::class, 'link_dataset_subjects', 'subject_id', 'document_id');
    }
}
