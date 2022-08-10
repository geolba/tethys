<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dataset;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DatasetReference extends Model
{
    use HasFactory;
    protected $table = 'document_references';
    public $timestamps = false;

    protected $fillable = ['value', 'label', 'type', 'relation'];

    public function dataset()
    {
        return $this->belongsTo(Dataset::class, 'document_id', 'id');
    }
}
