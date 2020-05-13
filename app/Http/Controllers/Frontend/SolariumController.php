<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Input;
use Illuminate\View\View;

class SolariumController extends Controller
{
    protected $client;

    public function __construct(\Solarium\Client $client)
    {
        $this->client = $client;
    }

    /**
     * Displays simple search form.
     */
    public function index(Request $request): View
    {
        if ($request->has('q') && $request->input('q') != "") {
            // Create a search query
            $query = $this->client->createSelect();
            
            // if (Input::get('q') != "") {
            //     // $query->setQuery(Input::get('q'));
            //     //better use placeholder to escape the search phrase:
            //     $query->setQuery('%P1%', array(Input::get('q')));
            // } else {
            //     $query = $this->client->createQuery($this->client::QUERY_SELECT);
            // }

            //Set the query string
            //$query->setQuery(Input::get('q'));
            $query->setQuery('%P1%', array($request->input('q')));


            // Create a DisMax query
            $dismax = $query->getDisMax();
            // Set the fields to query, and their relative weights
            $dismax->setQueryFields('title^3 abstract^2 subject^1');

            $facetSet = $query->getFacetSet();
            $facetSet->createFacetField('year')->setField('year');
            $facetSet->createFacetField('language')->setField('language');
            $facetSet->createFacetField('datatype')->setField('doctype');
            
            
            if ($request->has('year')) {
                $query->createFilterQuery('year')->setQuery(sprintf('year:%s', $request->input('year')));
            }
            if ($request->has('language')) {
                $query->createFilterQuery('language')->setQuery(sprintf('language:%s', $request->input('language')));
            }
            if ($request->has('datatype')) {
                $query->createFilterQuery('datatype')->setQuery(sprintf('doctype:%s', $request->input('datatype')));
            }

            // Execute the query and return the result
            $resultset = $this->client->select($query);

            // Pass the resultset to the view and return.
            return view('frontend.search.index', array(
                'q' => $request->input('q'),
                'resultset' => $resultset,
            ));
        }
        // No query to execute, just return the search form.
        return view('frontend.search.index');
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
            return response()->json('ERROR' . $e->getMessage(), 500);
        }
    }

    public function search()
    {
        $filter = "test"; //$request->input('query');

        // get a select query instance
        $query = $this->client->createSelect();

        // $query = $this->client->createSelect();
        $query->setQuery('title:' . $filter);
        // set a query (all prices starting from 12)
        // $query->setQuery('price:[12 TO *]');
        // set start and rows param (comparable to SQL limit) using fluent interface
        //$query->setStart(2)->setRows(20);

        // this executes the query and returns the result
        $resultset = $this->client->select($query);
        // display the total number of documents found by solr
        $response = 'NumFound: ' . $resultset->getNumFound();
        // show documents using the resultset iterator
        foreach ($resultset as $document) {
            $response = $response . '<hr/><table>';
            $response = $response . '<tr><th>id</th><td>' . $document->id . '</td></tr>';
            $response = $response . '<tr><th>title</th><td>' . $document->title_output . '</td></tr>';
            $response = $response . '<tr><th>abstract</th><td>' . $document->abstract_output . '</td></tr>';
            $response = $response . '</table>';
        }
        echo $response;
    }
}
