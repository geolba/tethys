<?php
namespace App\Library\Util;

use App\Library\Search\SolariumAdapter;
use App\Library\Search\SearchResult;
use Illuminate\Support\Facades\Log;

class SolrSearchSearcher
{
    /*
     * Holds numbers of facets
     */
    private $facetArray;



    public function __construct()
    {
    }


    /**
     *
     * @param SolrSearchQuery $query
     * @param bool $validateDocIds check document IDs coming from Solr index against database
     * @return SearchResult
     * @throws //Opus_SolrSearch_Exception If Solr server responds with an error or the response is empty.
     */
    public function search(SolrSearchQuery $query, bool $validateDocIds = true) : SearchResult
    {

        try {
            //Opus_Log::get()->debug("query: " . $query->getQ());

            // get service adapter for searching
            // $service = SearchService::selectSearchingService( null, 'solr' );
            $service = new SolariumAdapter("solr", config('solarium'));

            $filterText = $query->getQ();//"*:*"
            // basically create query
            $requestParameter = $service->createQuery()
                ->setFilter($filterText)
                ->setStart($query->getStart())
                ->setRows($query->getRows());
            //start:0
            // rows:1
            // fields:null
            // sort:null
            // union:null
            // filter:"aa"
            // facet:null
            // subfilters:null

            $requestParameter->setFields(array('*', 'score'));

            $searchResult = $service->customSearch($requestParameter);

            //if ( $validateDocIds )
            //{
            //    $searchResult->dropLocallyMissingMatches();
            //}

            return $searchResult;
        } catch (Exception $e) {
            return $this->mapException(null, $e);
        }
        // catch ( Opus_Search_InvalidServiceException $e ) {
        //     return $this->mapException( Opus_SolrSearch_Exception::SERVER_UNREACHABLE, $e );
        // }
        // catch( Opus_Search_InvalidQueryException $e ) {
        //     return $this->mapException( Opus_SolrSearch_Exception::INVALID_QUERY, $e );
        // }
    }

    /**
     * @param mixed $type
     * @param //Exception $previousException
     * @throws //Opus_SolrSearch_Exception
     * @return no-return
     */
    private function mapException($type, Exception $previousException)
    {
        $msg = 'Solr server responds with an error ' . $previousException->getMessage();
        //Opus_Log::get()->err($msg);
        Log::error($msg);

        //throw new Opus_SolrSearch_Exception($msg, $type, $previousException);
    }

    public function setFacetArray($array)
    {
        $this->facetArray = $array;
    }
}
