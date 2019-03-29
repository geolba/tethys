<?php
namespace App\Models;

use App\Models\Dataset;
use Illuminate\Database\Eloquent\Model;

class Coverage extends Model
{
    protected $table = 'coverage';
    public $timestamps = true;

    protected $fillable = [
        'elevation_min',
        'elevation_max',
        'elevation_absolut',
        'depth_min',
        'depth_max',
        'depth_absolut',
        'time_min',
        'time_max',
        'time_absolut',
        'xmin', 'xmax', 'ymin', 'ymax'
    ];

    public function dataset()
    {
        return $this->belongsTo(Dataset::class, 'dataset_id', 'id');
    }
}