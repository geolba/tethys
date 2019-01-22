<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dataset;

class Title extends Model
{
    protected $table = 'dataset_titles';
    public $timestamps = false;
    

    protected $fillable = [
    ];
    
    public function dataset()
    {
        return $this->belongsTo(Dataset::class, 'document_id', 'id');
    }
}
