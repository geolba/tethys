<?php
namespace App\Models;

use App\Models\Dataset;
use Illuminate\Database\Eloquent\Model;

class Coverage extends Model
{
    protected $table = 'coverage';
    public $timestamps = true;
    protected $dateFormat = 'Y-m-d H:i:s';
    // protected $dateFormat = 'd.m.Y H:i:s';

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
        'x_min', 'x_max', 'y_min', 'y_max'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'time_min',
        'time_max',
        'time_absolut',
    ];

    public function setTimeAbsolutAttribute($value)
    {
        $this->attributes['time_absolut'] = \Illuminate\Support\Carbon::createFromFormat('d.m.Y H:i:s', $value);
    }
    public function setTimeMinAttribute($value)
    {
        $this->attributes['time_min'] = \Illuminate\Support\Carbon::createFromFormat('d.m.Y H:i:s', $value);
    }
    public function setTimeMaxAttribute($value)
    {
        $this->attributes['time_max'] = \Illuminate\Support\Carbon::createFromFormat('d.m.Y H:i:s', $value);
    }

    /**
     * relationship to dataset
     *
     *
     */
    public function dataset()
    {
        return $this->belongsTo(Dataset::class, 'dataset_id', 'id');
    }
}
