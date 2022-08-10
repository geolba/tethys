<?php
namespace App\Models;

use App\Models\Dataset;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Coverage extends Model
{
    use HasFactory;
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
        'x_min', 'x_max', 'y_min', 'y_max',
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

    public function setTimeAbsolutAttribute($date)
    {
        $this->attributes['time_absolut'] = empty($date) ? null : \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s', $date);
    }
    public function setTimeMinAttribute($date)
    {
        $this->attributes['time_min'] = empty($date) ? null : \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s', $date);
    }
    public function setTimeMaxAttribute($date)
    {
        $this->attributes['time_max'] = empty($date) ? null : \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s', $date);
    }
        /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
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
