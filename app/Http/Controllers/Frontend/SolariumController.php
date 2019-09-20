<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Input;

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
        if (Input::has('q')) {
            // Create a search query
            $query = $this->client->createSelect();
    
            // Set the query string
            if (Input::get('q') != "") {
                $query->setQuery('%P1%', array(Input::get('q')));
            } else {
                $query = $this->client->createQuery($this->client::QUERY_SELECT);
            }
    
            // Execute the query and return the result
            $resultset = $this->client->select($query);
    
            // Pass the resultset to the view and return.
            return view('frontend.search.index', array(
                'q' => Input::get('q'),
                'resultset' => $resultset,
            ));
        }
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
        } catch (\Solarium\Exception $e) {
            return response()->json('ERROR', 500);
        }
    }

    public function search()
    {
        $filter = "test"; //$request->input('query');

        // get a select query instance
        $query = $this->client->createSelect();

        // $query = $this->client->createSelect();
        $query->setQuery('title:'. $filter);
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
            $response =  $response . '<hr/><table>';
            $response =  $response . '<tr><th>id</th><td>' . $document->id . '</td></tr>';
            $response =  $response . '<tr><th>title</th><td>' . $document->title_output . '</td></tr>';
            $response =  $response . '<tr><th>abstract</th><td>' . $document->abstract_output . '</td></tr>';
            $response =  $response . '</table>';
        }
        echo $response;
    }
}
