<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use Illuminate\View\View;

class PagesController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function datasets() : View
    {
        // $books = Book::with('category', 'shelf')->orderByTitle()->get();
        $documents = Dataset::orderByType()->get();
        return view('frontend.dataset.dataset', compact('documents'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $dataset = Dataset::findOrFail($id);
        $dataset->load('titles');
        $dataset->load('abstracts');

        $authors = $dataset->authors()
        ->orderBy('link_documents_persons.sort_order', 'desc')
        ->get();

        $contributors = $dataset->contributors()
        ->orderBy('link_documents_persons.sort_order', 'desc')
        ->get();

        $submitters = $dataset->persons()
        ->wherePivot('role', 'submitter')
        ->orderBy('link_documents_persons.sort_order', 'desc')
        ->get();

        // $authors = $dataset->persons()
        // ->wherePivot('role', 'author')
        // ->orderBy('link_documents_persons.sort_order', 'desc')
        // ->get();

        return view('frontend.dataset.show', compact('dataset', 'authors', 'contributors', 'submitters'));
    }
}
