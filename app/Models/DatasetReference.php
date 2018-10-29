<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dataset;

class DatasetReference extends Model
{
    protected $table = 'document_references';
    public $timestamps = false;

    protected $fillable = ['value', 'label', 'type', 'relation'];

    public function dataset()
    {
        return $this->belongsTo(Dataset::class, 'document_id', 'id');
    }
}
