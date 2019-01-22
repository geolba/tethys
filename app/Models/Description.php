<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dataset;

class Description extends Model
{
    protected $table = 'dataset_abstracts';
    public $timestamps = false;
    

    protected $fillable = [
    ];
    
    public function dataset()
    {
        return $this->belongsTo(Dataset::class, 'document_id', 'id');
    }
}
