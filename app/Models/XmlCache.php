<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dataset;
use Illuminate\Support\Facades\DB;

class XmlCache extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'document_xml_cache';
    public $timestamps = false;


    /**
     * primaryKey
     *
     * @var integer
     * @access protected
     */
    //protected $primaryKey = null;
    public $primaryKey = 'document_id';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['document_id', 'xml_version', 'server_date_modified', 'xml_data'];

    /**
     * Get the dataset that owns the xml cache.
     */
    public function dataset()
    {
        return $this->belongsTo(Dataset::class, 'document_id', 'id');
    }

    /**
     * Get dom document of 'xml_data' string
     *
     * @return \DOMDocument
     */
    public function getDomDocument()
    {
        $dom = new \DOMDocument('1.0', 'utf-8');
        $xmlData = $this->xml_data;
        $dom->loadXML($xmlData);
        return $dom;
    }

    /**
     * Check if a dataset in a specific xml version is already cached or not.
     *
     * @param mixed $datasetId
     * @param mixed $serverDateModified
     * @return bool Returns true on cached hit else false.
     */
    public function hasValidEntry($datasetId, $serverDateModified) : bool
    {
        $select = DB::table('document_xml_cache');
        $select->where('document_id', '=', $datasetId)
        ->where('server_date_modified', '=', $serverDateModified);
        
        $row = $select->first();

        if (null === $row) {
            return false;
        } else {
            return true;
        }
    }
}
