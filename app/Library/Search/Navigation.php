<?php
namespace App\Library\Search;

use App\Library\Util\QueryBuilder;
use App\Library\Util\Searchtypes;
use App\Library\Util\SolrSearchQuery;

/**
 * Class for navigation in search results.
 */
class Navigation
{

    /**
     * Builds query for Solr search.
     * @return SolrSearchQuery|void
     * @throws Application_Exception, Application_Util_BrowsingParamsException, Application_Util_QueryBuilderException
     */
    public static function getQueryUrl(\Illuminate\Http\Request $request) : SolrSearchQuery
    {
        $queryBuilder = new QueryBuilder();
        $queryBuilderInput = $queryBuilder->createQueryBuilderInputFromRequest($request);

        if (is_null($request->input('sortfield')) &&
                ($request->input('browsing') === 'true' || $request->input('searchtype') === 'collection')) {
            $queryBuilderInput['sortField'] = 'server_date_published';
        }

        if ($request->input('searchtype') === Searchtypes::LATEST_SEARCH) {
            return $queryBuilder->createSearchQuery(self::validateInput($queryBuilderInput, 10, 100));
        }

        $solrSearchQuery = $queryBuilder->createSearchQuery(self::validateInput($queryBuilderInput, 1, 100));
        return $solrSearchQuery;
        //$queryBuilder->createSearchQuery(self::validateInput($queryBuilderInput,1, 100));
    }

    /**
     * Adjust the actual rows parameter value if it is not between $min
     * and $max (inclusive). In case the actual value is smaller (greater)
     * than $min ($max) it is adjusted to $min ($max).
     *
     * Sets the actual start parameter value to 0 if it is negative.
     *
     * @param array $data An array that contains the request parameters.
     * @param int $lowerBoundInclusive The lower bound.
     * @param int $upperBoundInclusive The upper bound.
     * @return int Returns the actual rows parameter value or an adjusted value if
     * it is not in the interval [$lowerBoundInclusive, $upperBoundInclusive].
     *
     */
    private static function validateInput(array $input, $min = 1, $max = 100) : array
    {
        if ($input['rows'] > $max) {
            // $logger->warn("Values greater than $max are currently not allowed for the rows paramter.");
            $input['rows'] = $max;
        }
        if ($input['rows'] < $min) {
            // $logger->warn("rows parameter is smaller than $min: adjusting to $min.");
            $input['rows'] = $min;
        }
        if ($input['start'] < 0) {
            // $logger->warn("A negative start parameter is ignored.");
            $input['start'] = 0;
        }
        return $input;
    }
}
