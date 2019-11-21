<?php

namespace App\Library\Search;

use App\Models\Dataset;

class SolrDocumentXslt
{
    /**
     * @var XSLTProcessor
     */
    protected $processor;

    public function __construct($options)
    {
        //parent::__construct($options);

        try {
            $xslt = new \DomDocument;
            $xslt->load($options['xsltfile']);

            $this->processor = new \XSLTProcessor;
            $this->processor->importStyleSheet($xslt);
        } catch (Exception $e) {
            throw new Exception('invalid XSLT file for deriving Solr documents', 0, $e);
        }
    }

    public function toSolrDocument(Dataset $rdrDataset, \DOMDocument $solrDoc)
    {
        if (!($solrDoc instanceof \DOMDocument)) {
            throw new Exception('provided Solr document must be instance of DOMDocument');
        }

        $modelXml = $this->getModelXml($rdrDataset);//->saveXML();

        $solrDoc->preserveWhiteSpace = false;
        $solrDoc->loadXML($this->processor->transformToXML($modelXml));

        // if (Opus_Config::get()->log->prepare->xml) {
        //     $modelXml->formatOutput = true;
        //     Opus_Log::get()->debug("input xml\n" . $modelXml->saveXML());
        //     $solrDoc->formatOutput = true;
        //     Opus_Log::get()->debug("transformed solr xml\n" . $solrDoc->saveXML());
        // }

        return $solrDoc;
    }

    /**
     * Retrieves XML describing model data of provided TETHYS dataset.
     *
     * @param Dataset $rdrDataset
     * @return DOMDocument
     */
    protected function getModelXml(Dataset $rdrDataset)
    {
        $rdrDataset->fetchValues();
        // Set up caching xml-model and get XML representation of document.
        $xmlModel = new \App\Library\Xml\XmlModel();
        //$caching_xml_model = new Opus_Model_Xml;
       
        //$caching_xml_model->setModel($opusDoc);
        $xmlModel->setModel($rdrDataset);
        $xmlModel->excludeEmptyFields();
        //$xmlModel->setStrategy(new Opus_Model_Xml_Version1);
        //$cache = new Opus_Model_Xml_Cache($opusDoc->hasPlugin('Opus_Document_Plugin_Index'));
        //$xmlModel->setXmlCache($cache);
        $cache = ($rdrDataset->xmlCache) ?  $rdrDataset->xmlCache : new \App\Models\XmlCache();
        $xmlModel->setXmlCache($cache);

        $modelXml = $xmlModel->getDomDocument();
        
        // extract fulltext from file and append it to the generated xml.
        //$this->attachFulltextToXml($modelXml, $opusDoc->getFile(), $opusDoc->getId());

        return $modelXml;
    }
}
