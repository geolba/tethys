<?php
namespace App\Models;

use App\Models\Dataset;
use Illuminate\Database\Eloquent\Model;

class GeolocationBox extends Model
{
    protected $table = 'geolocation_box';
    public $timestamps = false;

    protected $fillable = ['xmin', 'xmax', 'ymin', 'ymax'];

    protected $casts = [
        'xmin' => 'float',
        'xmax' => 'float',
        'ymin' => 'float',
        'ymax' => 'float',
    ];

    public function dataset()
    {
        return $this->belongsTo(Dataset::class, 'dataset_id', 'id');
    }
}
