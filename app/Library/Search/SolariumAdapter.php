<?php

namespace App\Library\Search;

//use App\Library\Util\SolrSearchQuery;
use App\Library\Search\SearchResult;
use App\Library\Util\SearchParameter;
use Illuminate\Support\Facades\Log;
use App\Library\Search\SolariumDocument;
use App\Models\Dataset;
use \Solarium\QueryType\Select\Query\Query;

class SolariumAdapter
{
    protected $options;

    /**
     * @var \Solarium\Core\Client\Client
     */
    protected $client;

    public function __construct($serviceName, $options)
    {
        $this->options = $options;
        $this->client = new \Solarium\Client($options);

        // ensure service is basically available
        $ping = $this->client->createPing();
        $this->execute($ping, 'failed pinging service ' . $serviceName);
    }

    /**
     * Maps name of field returned by search engine into name of asset to use
     * on storing field's value in context of related match.
     *
     * This mapping relies on runtime configuration. Mapping is defined per
     * service in
     *
     * @param string $fieldName
     * @return string
     */
    protected function mapResultFieldToAsset($fieldName)
    {
        //if ( $this->options->fieldToAsset instanceof Zend_Config )
        //{
        //    return $this->options->fieldToAsset->get( $fieldName, $fieldName );
        //}
        return $fieldName;
    }

    public function getDomain()
    {
        return 'solr';
    }

    public function addDatasetsToIndex($datasets)
    {
        $datasets = $this->normalizeDocuments($datasets);
        $builder = new SolariumDocument($this->options);

        $slices = array_chunk($datasets, 16);
        // update documents of every chunk in a separate request
        foreach ($slices as $slice) {
            $update = $this->client->createUpdate();

            $updateDocs = array_map(function ($rdrDoc) use ($builder, $update) {
                return $builder->toSolrUpdateDocument($rdrDoc, $update->createDocument());
            }, $slice);

            // adding the document to the update query
            $update->addDocuments($updateDocs);
             // Then commit the update:
             $update->addCommit();
             $result = $this->client->update($update);

            //$this->execute($update, 'failed updating slice of documents');
        }

        // finally commit all updates
        // $update = $this->client->createUpdate();
       
        // $update->addCommit();

        // $this->execute($update, 'failed committing update of documents');

        return $this;
    }

    protected function normalizeDocuments($documents)
    {
        if (!is_array($documents)) {
            $documents = array($documents);
        }

        foreach ($documents as $document) {
            if (!($document instanceof Dataset)) {
                throw new InvalidArgumentException("invalid dataset in provided set");
            }
        }

        return $documents;
    }

    public function createQuery(): SearchParameter
    {
        return new SearchParameter();
    }

    public function customSearch(SearchParameter $queryParameter)
    {
        $search = $this->client->createSelect();
        $solariumQuery = $this->applyParametersToSolariumQuery($search, $queryParameter, false);
        $searchResult = $this->processQuery($solariumQuery);
        return $searchResult;
    }

    protected function applyParametersToSolariumQuery(Query $query, SearchParameter $parameters, $preferOriginalQuery)
    {
        if ($parameters) {
            //$subfilters = $parameters->getSubFilters();
            //if ( $subfilters !== null ) {
            //    foreach ( $subfilters as $name => $subfilter ) {
            // if ( $subfilter instanceof Opus_Search_Solr_Filter_Raw
            //|| $subfilter instanceof Opus_Search_Solr_Solarium_Filter_Complex ) {
            //            $query->createFilterQuery( $name )
            //                  ->setQuery( $subfilter->compile( $query ) );
            //        }
            //    }
            //}

            // $filter = $parameters->getFilter();//"aa"
            // if ( $filter instanceof Opus_Search_Solr_Filter_Raw || $filter instanceof Opus_Search_Solr_Solarium_Filter_Complex ) {
            // if ( !$query->getQuery() || !$preferOriginalQuery ) {
            // $compiled = $filter->compile( $query );
            // if ( $compiled !== null ) {
            // // compile() hasn't implicitly assigned query before
            // $query->setQuery( $compiled );
            // }
            // }
            // }

            $filter = $parameters->getFilter(); //"aa"  all: '*:*'
            if ($filter !== null) {
                //$query->setStart( intval( $start ) );
                //$query->setQuery('%P1%', array($filter));
                $query->setQuery($filter);
            }

            $start = $parameters->getStart();
            if ($start !== null) {
                $query->setStart(intval($start));
            }

            $rows = $parameters->getRows();
            if ($rows !== null) {
                $query->setRows(intval($rows));
            }

            $union = $parameters->getUnion();
            if ($union !== null) {
                $query->setQueryDefaultOperator($union ? 'OR' : 'AND');
            }

            $fields = $parameters->getFields();
            if ($fields !== null) {
                $query->setFields($fields);
            }

            $sortings = $parameters->getSort();
            if ($sortings !== null) {
                $query->setSorts($sortings);
            }

            $facet = $parameters->getFacet();
            if ($facet !== null) {
                $facetSet = $query->getFacetSet();
                foreach ($facet->getFields() as $field) {
                    $facetSet->createFacetField($field->getName())
                        ->setField($field->getName())
                        ->setMinCount($field->getMinCount())
                        ->setLimit($field->getLimit())
                        ->setSort($field->getSort() ? 'index' : null);
                }
                if ($facet->isFacetOnly()) {
                    $query->setFields(array());
                }
            }
        }
        return $query;
    }

    protected function execute($query, $actionText)
    {
        $result = null;
        try {
            $result = $this->client->execute($query);
        } catch (\Solarium\Exception\HttpException $e) {
            sprintf('%s: %d %s', $actionText, $e->getCode(), $e->getStatusMessage());
        } finally {
            return $result;
        }

        // if ( $result->getStatus() ) {
        // throw new Opus_Search_Exception( $actionText, $result->getStatus() );
        // }
    }

    protected function processQuery(\Solarium\QueryType\Select\Query\Query $query): SearchResult
    {
        // send search query to service
        $request = $this->execute($query, 'failed querying search engine');

        //$count = $request->getDocuments();
        // create result descriptor
        $result = SearchResult::create()
            ->setAllMatchesCount($request->getNumFound())
            ->setQueryTime($request->getQueryTime());

        // add description on every returned match
        $excluded = 0;
        foreach ($request->getDocuments() as $document) {
            /** @var \Solarium\QueryType\Select\Result\Document $document */
            $fields = $document->getFields();

            if (array_key_exists('id', $fields)) {
                $match = $result->addMatch($fields['id']);

                foreach ($fields as $fieldName => $fieldValue) {
                    switch ($fieldName) {
                        case 'id':
                            break;

                        case 'score':
                            $match->setScore($fieldValue);
                            break;

                        case 'server_date_modified':
                            $match->setServerDateModified($fieldValue);
                            break;

                        case 'fulltext_id_success':
                            $match->setFulltextIDsSuccess($fieldValue);
                            break;

                        case 'fulltext_id_failure':
                            $match->setFulltextIDsFailure($fieldValue);
                            break;

                        default:
                            $match->setAsset($fieldName, $fieldValue);
                            //$match->setAsset( $this->mapResultFieldToAsset( $fieldName ), $fieldValue );
                            break;
                    }
                }
            } else {
                $excluded++;
            }
        }

        if ($excluded > 0) {
            Log::warning(sprintf(
                'search yielded %d matches not available in result set for missing ID of related document',
                $excluded
            ));
        }

        return $result;
    }
}
