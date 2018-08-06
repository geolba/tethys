<?php

/**
 * Footer
 *
 * Main footer file for the theme.
 *
 * @category   Components
 * @package    ResearchRepository
 * @subpackage Publish
 * @author     Your Name <yourname@example.com>
 * @license    https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link       https://gisgba.geologie.ac.at
 * @since      1.0.0
 */
namespace App\Library\Xml;

use App\XmlCache;
use Illuminate\Support\Facades\Log;

class XmlModel
{
    /**
     * Holds current configuration.
     * @var Conf
     */
    private $_config = null;

    /**
     * Holds current xml strategy object.
     * @var Strategy
     */
    private $_strategy = null;

    /**
     * TODO
     * @var XmlCache
     */
    private $_cache = null;


    /**
     * Do some initial stuff like setting of a XML version and an empty
     * configuration.
     */
    public function __construct()
    {
        $this->_strategy = new Strategy();// Opus_Model_Xml_Version1;
        $this->_config = new Conf();
        $this->_strategy->setup($this->_config);
    }

    /**
     * Set a new XML version with current configuration up.
     *
     * @param Strategy $strategy Version of Xml to process
     *
     * @return XmlModel fluent interface.
     */
    public function setStrategy(Strategy $strategy)
    {
        $this->_strategy = $strategy;
        $this->_strategy->setup($this->_config);
        return $this;
    }

    /**
     * Set a new XML version with current configuration up.
     *
     * @param XmlCache $cache cach table
     *
     * @return XmlModel fluent interface.
     */
    public function setXmlCache(XmlCache $cache)
    {
        $this->_cache = $cache;
        return $this;
    }

    /**
     * Return cache table.
     *
     * @return XmlCache
     */
    public function getXmlCache()
    {
        return $this->_cache;
    }

    /**
     * Set the Model for XML generation.
     *
     * @param \App\Dataset $model Model to serialize.
     *
     * @return XmlModel Fluent interface.
     */
    public function setModel($model)
    {
        $this->_config->model = $model;
        return $this;
    }

    /**
     * Define that empty fields (value===null) shall be excluded.
     *
     * @return XmlModel Fluent interface
     */
    public function excludeEmptyFields()
    {
        $this->_config->excludeEmpty = true;
        return $this;
    }

    /**
     * If a model has been set this method generates and returnes
     * DOM representation of it.
     *
     * @return \DOMDocument DOM representation of the current Model.
     */
    public function getDomDocument()
    {
        $dataset = $this->_config->model;

        $domDocument = $this->getDomDocumentFromXmlCache();
        if (!is_null($domDocument)) {
            return $domDocument;
        }

        //create xml:
        $domDocument = $this->_strategy->getDomDocument();
        //if caching is not desired, return domDocument
        if (is_null($this->_cache)) {
            return $domDocument;
        } else {
            //create cache relation
            $this->_cache->fill(array(
                'document_id' => $dataset->id,
                'xml_version' => (int)$this->_strategy->getVersion(),
                'server_date_modified' => $dataset->server_date_modified,
                'xml_data' => $domDocument->saveXML()
            ));
            $this->_cache->save();

            Log::debug(__METHOD__ . ' cache refreshed for ' . get_class($dataset) . '#' . $dataset->id);
            return $domDocument;
        }
    }

    /**
     * This method tries to load the current model from the xml cache.  Returns
     * null in case of an error/cache miss/cache disabled.  Returns DOMDocument
     * otherwise.
     *
     * @return \DOMDocument DOM representation of the current Model.
     */
    private function getDomDocumentFromXmlCache()
    {
        $dataset = $this->_config->model;
        if (null === $this->_cache) {
            //$logger->debug(__METHOD__ . ' skipping cache for ' . get_class($model));
            Log::debug(__METHOD__ . ' skipping cache for ' . get_class($dataset));
            return null;
        }
        //$cached = $this->_cache->hasValidEntry(
        //    $dataset->id,
        //    (int) $this->_strategy->getVersion(),
        //    $dataset->server_date_modified
        //);

        //$cached = false;
        $cache = XmlCache::where('document_id', $dataset->id)
            ->first();// model or null
        if (!$cache) {
            Log::debug(__METHOD__ . ' cache miss for ' . get_class($dataset) . '#' . $dataset->id);
            return null;
        } else {
            return $cache->getDomDocument();
        }
    }
}
