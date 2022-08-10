<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dataset;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Title extends Model
{
    use HasFactory;
    protected $table = 'dataset_titles';
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
