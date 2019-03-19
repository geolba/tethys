<?php
namespace App\Library\Util;

use App\Library\Util\Searchtypes;
use App\Library\Util\SolrSearchQuery;

class QueryBuilder
{
    private $_logger;
    private $_filterFields;
    private $_searchFields;
    private $_export = false;

    const SEARCH_MODIFIER_CONTAINS_ALL = "contains_all";
    const SEARCH_MODIFIER_CONTAINS_ANY = "contains_any";
    const SEARCH_MODIFIER_CONTAINS_NONE = "contains_none";

    const MAX_ROWS = 2147483647;

    /**
     *
     * @param boolean $export
     */
    public function __construct($export = false)
    {
        $this->_filterFields = array();

        // $filters = Opus_Search_Config::getFacetFields();
        // if ( !count( $filters ) ) {
        // $this->_logger->debug( 'key searchengine.solr.facets is not present in config. skipping filter queries' );
        // } else {
        //     $this->_logger->debug( 'searchengine.solr.facets is set to ' . implode( ',', $filters ) );
        // }

        // foreach ($filters as $filterfield) {
        //     if ($filterfield == 'year_inverted') {
        //         $filterfield = 'year';
        //     }
        //     array_push($this->_filterFields, trim($filterfield));
        // }

        $this->_searchFields = array('author', 'title', 'persons', 'referee', 'abstract', 'fulltext', 'year');
        $this->_export = $export;
    }

    /**
     *
     * @param $request
     * @return array
     */
    public function createQueryBuilderInputFromRequest($request) : array
    {
        if (is_null($request->all())) {
            throw new Application_Util_QueryBuilderException('Unable to read request data.Search cannot be performed.');
        }

        if (is_null($request->input('searchtype'))) {
            throw new Application_Util_QueryBuilderException('Unspecified search type: unable to create query.');
        }

        if (!Searchtypes::isSupported($request->input('searchtype'))) {
            throw new Application_Util_QueryBuilderException(
                'Unsupported search type ' . $request->input('searchtype') . ' : unable to create query.'
            );
        }

        $this->validateParamsType($request);

        if ($request->input('sortfield')) {
            $sorting = array($request->input('sortfield'), 'asc');
        } else {
            //$sorting = Opus_Search_Query::getDefaultSorting();
            $sorting = array('score', 'desc' );
        }

        $input = array(
            'searchtype' => $request->input('searchtype'),
            'start' => $request->input('start'),//, Opus_Search_Query::getDefaultStart()),
            'rows' => $request->input('rows'),// Opus_Search_Query::getDefaultRows()),
            'sortField' => $sorting[0],
            'sortOrder' => $request->input('sortorder', $sorting[1]),
            'docId' => $request->input('docId'),
            'query' => $request->input('query', '*:*')
        );

        //if ($this->_export) {
        //    $maxRows = self::MAX_ROWS;
        //    // pagination within export was introduced in OPUS 4.2.2
        //    $startParam = $request->input('start', 0);
        //    $rowsParam = $request->input('rows', $maxRows);
        //    $start = intval($startParam);
        //    $rows = intval($rowsParam);
        //    $input['start'] = $start > 0 ? $start : 0;
        //    $input['rows'] = $rows > 0 || ($rows == 0 && $rowsParam == '0') ? $rows : $maxRows;
        //    if ($input['start'] > $maxRows) {
        //        $input['start'] = $maxRows;
        //    }
        //    if ($input['rows'] + $input['start'] > $maxRows) {
        //        $input['rows'] = $maxRows - $start;
        //    }
        //}

        foreach ($this->_searchFields as $searchField) {
            $input[$searchField] = $request->input($searchField, '');
            $input[$searchField . 'modifier'] = $request->input(
                $searchField . 'modifier',
                self::SEARCH_MODIFIER_CONTAINS_ALL
            );
        }

        // foreach ($this->_filterFields as $filterField) {
        //     $param = $filterField . 'fq';
        //     $input[$param] = $request->getParam($param, '');
        // }


        // if ($request->getParam('searchtype') === Searchtypes::COLLECTION_SEARCH
        //         || $request->input('searchtype') === Searchtypes::SERIES_SEARCH)
        //         {
        //     $searchParams = new Application_Util_BrowsingParams($request, $this->_logger);
        //     switch ($request->input('searchtype')) {
        //         case Searchtypes::COLLECTION_SEARCH:
        //             $input['collectionId'] = $searchParams->getCollectionId();
        //             break;
        //         case Searchtypes::SERIES_SEARCH:
        //             $input['seriesId'] = $searchParams->getSeriesId();
        //             break;
        //     }
        // }

        return $input;
    }

    /**
     * Checks if all given parameters are of type string. Otherwise, throws Application_Util_QueryBuilderException.
     *
     * @throws //Application_Util_QueryBuilderException
     */
    private function validateParamsType($request)
    {
        $paramNames = array(
            'searchtype',
            'start',
            'rows',
            'sortField',
            'sortOrder',
            'search',
            'collectionId',
            'seriesId'
        );
        foreach ($this->_searchFields as $searchField) {
            array_push($paramNames, $searchField, $searchField . 'modifier');
        }
        foreach ($this->_filterFields as $filterField) {
            array_push($paramNames, $filterField . 'fq');
        }

        foreach ($paramNames as $paramName) {
            $paramValue = $request->input($paramName, null);
            if (!is_null($paramValue) && !is_string($paramValue)) {
                throw new Application_Util_QueryBuilderException('Parameter ' . $paramName . ' is not of type string');
            }
        }
    }

    /**
     *
     * @param array $input
     * @return SolrSearchQuery
     */
    public function createSearchQuery($input) : SolrSearchQuery
    {
        if ($input['searchtype'] === Searchtypes::SIMPLE_SEARCH) {
            return $this->createSimpleSearchQuery($input);
            //return $this->createAllSearchQuery($input);
        }

        if ($input['searchtype'] === Searchtypes::ALL_SEARCH) {
            return $this->createAllSearchQuery($input);
        }
        return $this->createSimpleSearchQuery($input);
    }

    // private function createIdSearchQuery($input) {
    //     $this->_logger->debug("Constructing query for id search.");

    //     if (is_null($input['docId'])) {
    //         throw new Application_Exception("No id provided.", 404);
    //     }

    //     $query = new Opus_SolrSearch_Query(Opus_SolrSearch_Query::DOC_ID);
    //     $query->setField('id', $input['docId']);

    //     if ($this->_export) {
    //         $query->setReturnIdsOnly(true);
    //     }

    //     $this->_logger->debug("Query $query complete");
    //     return $query;
    // }

    private function createAllSearchQuery($input)
    {
        //$this->_logger->debug("Constructing query for all search.");

        $query = new SolrSearchQuery(SolrSearchQuery::ALL_DOCS);
        $query->setStart("0");//$input['start']);
        //$query->setRows($input['rows']);
        $query->setRows("100");
        $query->setSortField($input['sortField']);
        $query->setSortOrder($input['sortOrder']);

        //$this->addFiltersToQuery($query, $input);

        //if ($this->_export) {
        //    $query->setReturnIdsOnly(true);
        //}

        //$this->_logger->debug("Query $query complete");
        return $query;
    }

    private function createSimpleSearchQuery($input) : SolrSearchQuery
    {
        // $this->_logger->debug("Constructing query for simple search.");

        $solrQuery = new SolrSearchQuery(SolrSearchQuery::SIMPLE);
        $solrQuery->setStart($input['start']);
        $solrQuery->setRows("10");//$input['rows']);
        $solrQuery->setSortField($input['sortField']);
        $solrQuery->setSortOrder($input['sortOrder']);

        $solrQuery->setCatchAll($input['query']);
        //$this->addFiltersToQuery($solrQuery, $input);

        // if ($this->_export) {
        //     $solrQuery->setReturnIdsOnly(true);
        // }

        // $this->_logger->debug("Query $solrQuery complete");
        return $solrQuery;
    }

    private function addFiltersToQuery($query, $input)
    {
        foreach ($this->_filterFields as $filterField) {
            $facetKey = $filterField . 'fq';
            $facetValue = $input[$facetKey];
            if ($facetValue !== '') {
                $this->_logger->debug(
                    "request has facet key: $facetKey - value is: $facetValue - corresponding facet is: $filterField"
                );
                $query->addFilterQuery($filterField, $facetValue);
            }
        }
    }
}
