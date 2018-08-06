<?php

namespace App\Http\Controllers;

use App\Dataset;
use App\Book;
use App\Category;
use App\Shelf;
use App\Periode;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PagesController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function documents() : View
    {
        // $books = Book::with('category', 'shelf')->orderByTitle()->get();
        $documents = Dataset::orderByType()->get();
        return view('rdr.document.documents', compact('documents'));
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
        return view('rdr.document.show', compact('document'));
    }
}
