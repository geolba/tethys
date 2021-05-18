<?php
namespace App\Models;

use App\Models\Dataset;
use Illuminate\Database\Eloquent\Model;

class DatasetIdentifier extends Model
{
    protected $table = 'dataset_identifiers';
    protected $guarded = array();

    /**
     * The dataset that belong to the DocumentIdentifier.
     */
    public function dataset()
    {
        return $this->belongsTo(Dataset::class, 'dataset_id', 'id');
    }
}
