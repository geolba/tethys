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
        $document = Dataset::findOrFail($id);
        $document->load('titles');
        $document->load('abstracts');
        return view('frontend.dataset.show', compact('document'));
    }
}
