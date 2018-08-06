<?php
namespace App;

use App\Dataset;

/**
 * DocumentFinder short summary.
 *
 * DocumentFinder description.
 *
 * @version 1.0
 * @author kaiarn
 */
class DatasetFinder
{
     /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    private $_select = null;
    /**
     * Create new instance of Opus_DocumentList class.  The created object
     * allows to get custom subsets (or lists) of all existing Opus_Documents.
     */
    public function __construct()
    {
        // $table = Opus_Db_TableGateway::getInstance(self::$_tableGatewayClass);

        // $this->_db = $table->getAdapter();
        // $this->_select = $this->_db->select()->from(array('d' => 'documents'));

        $this->_select = Dataset::query(); //>select('name', 'email as user_email')
    }

    /**
     * Add constraints to be applied on the result set.
     *
     * @param  string $serverStateArray
     * @return DatasetFinder Fluent interface.
     */
    public function setServerStateInList($serverStateArray)
    {
        $this->_select->whereIn('server_state', $serverStateArray);
        //$this->_select->where('server_state IN (?)', $serverStateArray);
        return $this;
    }

    /**
     * Add constraints to be applied on the result set.
     *
     * @param  string $type
     * @return DatasetFinder Fluent interface.
     */
    public function setType($type)
    {
        $this->_select->where('type', $type);
        return $this;
    }

     /**
     * Add constraints to be applied on the result set.
     *
     * @param  string $type
     * @return DatasetFinder Fluent interface.
     */
    public function setServerState($serverState)
    {
        //$this->_select->where('server_state', '=', $serverState);
        $this->_select->where('server_state', 'LIKE', "%".$serverState."%");
        return $this;
    }

    /**
     * Returns a list of distinct document types for the given constraint set.
     *
     * @return array
     */
    public function groupedTypesPlusCount()
    {
        //$this->_select->reset('columns');
        $test = $this->_select
        //->select("type") // "count(DISTINCT id)");
        ->selectRaw('type, count(DISTINCT id) as count')
        ->groupBy('type')
        ->pluck('count', 'type')
        ->toArray();
        return $test;
    }

    /**
     * Returns the number of (distinct) documents for the given constraint set.
     *
     * @return int
     */
    public function count()
    {
        $this->_select->count();
    }

     /**
     * Returns a list of (distinct) document ids for the given constraint set.
     *
     * NOTE: It was not possible to make sure only DISTINCT identifiers are returned. Therefore array_unique is used.
     * See OPUSVIER-3644 for more information.
     *
     * @return array
     */
    public function ids()
    {
        //return array_unique($this->_db->fetchCol($this->getSelectIds()));
        return $this->_select->pluck('id')->toArray();
    }
}
