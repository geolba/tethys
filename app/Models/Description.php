<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dataset;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Description extends Model
{
    use HasFactory;
    protected $table = 'dataset_abstracts';
    public $timestamps = false;
    

    protected $fillable = [
        'value',
        'type',
        'language'
    ];
    
    public function dataset()
    {
        return $this->belongsTo(Dataset::class, 'document_id', 'id');
    }
}
