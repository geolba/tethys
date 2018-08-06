<?php
namespace App\Http\Controllers;

use App\Book;
use App\Dataset;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Library\Search\Navigation;
use App\Library\Util\SolrSearchSearcher;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    private $_query;
    private $_numOfHits;
    private $_searchtype;
    private $_resultList;
    private $_facetMenu;

    protected $client;

    /**
     * Initialize controller.
     */
    public function __construct(\Solarium\Client $client)
    {
        $this->client = $client;
        // $config = config('solarium');
        // $config = array(
        // 'endpoint' => array(
        // 'localhost' => array(
        // 'host' => '127.0.0.1',
        // 'port' => '8983',
        // 'path' => '/solr/#',
        // 'core' => 'opus4'
        // )
        // )
        // );
        // $this->client = new \Solarium\Client($config);
    }

    public function ping()
    {
        // create a ping query
        $ping = $this->client->createPing();

        // execute the ping query
        try {
            $this->client->ping($ping);
            return response()->json('OK');
        } catch (\Solarium\Exception\HttpException $e) {
            return response()->json('ERROR', 500);
        }
    }

    public function search1(Request $request) : View
    {
        $this->_request = $request;
        $data=$request->all();
        //$this->_searchtype = $request->input('searchtype');
        $this->_searchtype = $request->input('searchtype');
        return view('rdr.solrsearch.index');
    }

    public function search(Request $request) : View
    {
        Log::info('Received new search request. Redirecting to search action of IndexController.');
        $this->_request = $request;

        //$filter =$request->input('query');
        // $query = $this->client->createSelect();
        // $query->setQuery('%P1%', array($filter));
        // // $query->setQuery('*:*');

        // $results = $this->client->select($query);
        // // // display the total number of documents found by solr
        // echo 'NumFound: ' .$results->getNumFound();

        //$this->_query = Navigation::getQueryUrl($request);
        $query = $this->buildQuery();
        if (!is_null($query)) {
            $this->_query = $query;
            $this->performSearch();

            // set start and rows param (comparable to SQL limit) using fluent interface
            //$query->setStart(2)->setRows(20);
            // set fields to fetch (this overrides the default setting 'all fields')
            //$query->setFields(array('id','year'));


            $results = $this->_resultList->getResults();
            $numOfHits = $this->_numOfHits;


            return view('rdr.solrsearch.index', compact('results', 'numOfHits'));
        }
        return view('rdr.solrsearch.index');
    }

    /**
     * Displays simple search form.
     */
    public function index() : View
    {
        $totalNumOfDocs = Dataset::count();
        return view('rdr.solrsearch.index', compact('totalNumOfDocs'));
    }


    public function searchDb(Request $request) : View
    {
        $searchType = "simple";
        $params = $request->all();
        //build query
        $this->_searchtype = $request->input('searchtype');


        // Gets the query string from our form submission
        //$query = Request::input('search');
        $filter = $request->input('search');

        //$query = Input::get('search', '');
        // Returns an array of articles that have the query string located somewhere within
        // our articles titles. Paginates them so we can break up lots of search results.
        $books = Book::where('title', 'LIKE', '%' . $filter . '%')
            ->get();//paginate(10);

        // returns a view and passes the view the list of articles and the original query.
        return view('rdr.solrsearch.index', compact('books'));
    }


    #region private helper

    private function buildQuery()
    {
        $request = $this->_request;
        $this->_searchtype = $request->input('searchtype');
        return Navigation::getQueryUrl($request);
    }

    /**
     * TODO this should happen in model class so it can be tested directly
     */
    private function performSearch()
    {
        //$this->getLogger()->debug('performing search');
        try {
            $searcher = new SolrSearchSearcher();
            // $openFacets = $this->_facetMenu->buildFacetArray( $this->getRequest()->getParams() );
            // $searcher->setFacetArray($openFacets);
            $this->_resultList = $searcher->search($this->_query);
            // $this->view->openFacets = $openFacets;
        } catch (Exception $e) {
            // $this->getLogger()->err(__METHOD__ . ' : ' . $e);
            //throw new Application_SearchException($e);
            echo 'Exception abgefangen: ', $e->getMessage(), "\n";
        }
        $this->_numOfHits = $this->_resultList->getNumberOfHits();
    }
    #endregion private helper
}
