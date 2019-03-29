<?php

namespace App\Library\Search;

use App\Models\Dataset;
use Solarium\QueryType\Update\Query\Document\Document;
use Solarium\QueryType\Update\Query\Document\DocumentInterface;

class SolariumDocument extends SolrDocumentXslt
{
    public function __construct($options)
    {
        parent::__construct($options);
    }
    
    public function toSolrUpdateDocument(Dataset $rdrDataset, DocumentInterface $solrDoc)
    {
        if (!($solrDoc instanceof Document)) {
            throw new \Exception('provided Solr document must be instance of Solarium Update Document');
        }
        
        // convert Opus document to Solr XML document for supporting custom transformations
        $solrDomDoc = parent::toSolrDocument($rdrDataset, new \DomDocument());
        
        // read back fields from generated Solr XML document
        $solrXmlDoc = simplexml_import_dom($solrDomDoc)->doc[0];

        $solrDoc->clear();
        foreach ($solrXmlDoc->field as $field) {
            $solrDoc->addField(strval($field['name']), strval($field));
        }

        return $solrDoc;
    }
}
