<?php
namespace App\Library\Search;

/**
 * Implements API for describing successful response to search query.
 */

use App\Library\Util\SearchResultMatch;

class SearchResult
{
    protected $data = array(
        'matches'   => null,
        'count'     => null,
        'querytime' => null,
        'facets'    => null,
    );
    protected $validated = false;

    public function __construct()
    {
    }

    /**
     * @return SearchResult
     */
    public static function create()
    {
        return new static();
    }

    /**
     * Assigns matches returned in response to search query.
     *
     * @param mixed $documentId ID of document considered match of related search query
     * @return SearchResultMatch
     */
    public function addMatch($documentId)
    {
        if (!is_array($this->data['matches'])) {
            $this->data['matches'] = array();
        }
        $match = SearchResultMatch::create($documentId);
        $this->data['matches'][] = $match;

        return $match;
    }

    /**
     * Sets number of all matching documents.
     *
     * @note This may include documents not listed as matches here due to using
     *       paging parameters on query.
     *
     * @param int $allMatchesCount number of all matching documents
     * @return $this fluent interface
     */
    public function setAllMatchesCount($allMatchesCount)
    {
        if (!is_null($this->data['count'])) {
            throw new RuntimeException('must not set count of all matches multiple times');
        }

        if (!ctype_digit(trim($allMatchesCount))) {
            throw new InvalidArgumentException('invalid number of overall matches');
        }
        $this->data['count'] = intval($allMatchesCount);

        return $this;
    }

    /**
     * Sets information on time taken for querying search engine.
     *
     * @param string $time
     * @return $this fluent interface
     */
    public function setQueryTime($time)
    {
        if (!is_null($this->data['querytime'])) {
            throw new RuntimeException('must not set query time multiple times');
        }
        if (!is_null($time)) {
            $this->data['querytime'] = trim($time);
        }

        return $this;
    }

    /**
     * Adds another result of faceted search to current result set.
     *
     * @param string $facetField name of field result of faceted search is related to
     * @param string $text description on particular faceted result on field (e.g. single value in field)
     * @param int $count number of occurrences of facet on field in all matches
     * @return $this fluent interface
     *
     * TODO special year_inverted facet handling should be moved to separate class
     */
    public function addFacet($facetField, $text, $count)
    {
        $facetField = strval($facetField);

        // remove inverted sorting prefix from year values
        if ($facetField === 'year_inverted') {
            $text = explode(':', $text, 2)[1];

            // treat 'year_inverted' as if it was 'year'
            $facetField = 'year';
        }

        // treat 'year_inverted' as if it was 'year'
        if ($facetField === 'year_inverted') {
            $facetField = 'year';
        }

        if (!is_array($this->data['facets'])) {
            $this->data['facets'] = array();
        }

        if (!array_key_exists($facetField, $this->data['facets'])) {
            $this->data['facets'][$facetField] = array();
        }

        $this->data['facets'][$facetField][] = new Opus_Search_Result_Facet($text, $count);

        return $this;
    }

    /**
     * Retrieves results of faceted search.
     *
     * @return Opus_Search_Result_Facet[][] map of fields' names into sets of facet result per field
     */
    public function getFacets()
    {
        return is_null($this->data['facets']) ? array() : $this->data['facets'];
    }

    /**
     * Retrieves set of facet results on single field selected by name.
     *
     * @param string $fieldName name of field returned facet result is related to
     * @return Opus_Search_Result_Facet[] set of facet results on selected field
     */
    public function getFacet($fieldName)
    {
        if ($this->data['facets'] && array_key_exists($fieldName, $this->data['facets'])) {
            return $this->data['facets'][$fieldName];
        }

        return array();
    }

    /**
     * Retrieves set of matching and locally existing documents returned in
     * response to some search query.
     *
     * @return Opus_Search_Result_Match[]
     */
    public function getReturnedMatches()
    {
        if (is_null($this->data['matches'])) {
            return array();
        }

        // map AND FILTER set of returned matches ensuring to list related
        // documents existing locally, only
        $matches = array();

        foreach ($this->data['matches'] as $match) {
            try {
                /** @var SearchResultMatch $match */
                // $match->getDocument();
                $matches[] = $match;
            } catch (Opus_Document_Exception $e) {
                Opus_Log::get()->warn('skipping matching but locally missing document #' . $match->getId());
            }
        }
        return $matches;
    }

    /**
     * Retrieves set of matching documents' IDs returned in response to some
     * search query.
     *
     * @note If query was requesting to retrieve non-qualified matches this set
     *       might include IDs of documents that doesn't exist locally anymore.
     *
     * @return int[]
     */
    public function getReturnedMatchingIds()
    {
        if (is_null($this->data['matches'])) {
            return array();
        }

        return array_map(function ($match) {
            /** @var SearchResultMatch $match */
            return $match->getId();
        }, $this->data['matches']);
    }

    /**
     * Retrieves set of matching documents.
     *
     * @note This is provided for downward compatibility, though it's signature
     *       has changed in that it's returning set of Opus_Document instances
     *       rather than set of Opus_SolrSearch_Result instances.
     *
     * @note The wording is less specific in that all information in response to
     *       search query may considered results of search. Thus this new API
     *       prefers "matches" over "results".
     *
     * @deprecated
     * @return Opus_Document[]
     */
    public function getResults()
    {
        return $this->getReturnedMatches();
    }

    /**
     * Removes all returned matches referring to Opus documents missing in local
     * database.
     *
     * @return $this
     */
    public function dropLocallyMissingMatches()
    {
        if (!$this->validated) {
            $finder = new Opus_DocumentFinder();

            $returnedIds = $this->getReturnedMatchingIds();
            $existingIds = $finder
                ->setServerState('published')
                ->setIdSubset($returnedIds)
                ->ids();

            if (count($returnedIds) !== count($existingIds)) {
                Opus_Log::get()->err(sprintf(
                    "inconsistency between db and search index: index returns %d documents, but only %d found in db",
                    count($returnedIds),
                    count($existingIds)
                ));

                // update set of returned matches internally
                $this->data['matches'] = array();
                foreach ($existingIds as $id) {
                    $this->addMatch($id);
                }
                // set mark to prevent validating matches again
                $this->validated = true;
            }
        }
        return $this;
    }

    /**
     * Retrieves overall number of matches.
     *
     * @note This number includes matches not included in fetched subset of
     *       matches.
     *
     * @return int
     */
    public function getAllMatchesCount()
    {
        if (is_null($this->data['count'])) {
            throw new RuntimeException('count of matches have not been provided yet');
        }
        return $this->data['count'];
    }

    /**
     * Retrieves overall number of matches.
     *
     * @note This is provided for downward compatibility.
     *
     * @deprecated
     * @return int
     */
    public function getNumberOfHits()
    {
        return $this->getAllMatchesCount();
    }

    /**
     * Retrieves information on search query's processing time.
     *
     * @return mixed
     */
    public function getQueryTime()
    {
        return $this->data['querytime'];
    }

    public function __get($name)
    {
        switch (strtolower(trim($name))) {
            case 'matches':
                return $this->getReturnedMatches();

            case 'allmatchescount':
                return $this->getAllMatchesCount();

            case 'querytime':
                return $this->getQueryTime();
            default:
                throw new RuntimeException('invalid request for property ' . $name);
        }
    }
}
