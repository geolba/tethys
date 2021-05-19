<?php
namespace App\Models;

use App\Models\Dataset;
use Illuminate\Database\Eloquent\Model;

class DatasetIdentifier extends Model
{
    protected $table = 'dataset_identifiers';
    protected $guarded = array();
    public $timestamps = true;

    //See the array called $touches? This is where you put all the relationships you want to get updated_at as soon as this Model is updated
    protected $touches = ['dataset'];

    /**
     * The dataset that belong to the DocumentIdentifier.
     */
    public function dataset()
    {
        return $this->belongsTo(Dataset::class, 'dataset_id', 'id');
    }
}
