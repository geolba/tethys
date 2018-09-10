<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Book;
use App\Models\Project;
use App\Shelf;
use App\Http\Requests\BookRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() : View
    {
        //$books = Book::with('category', 'shelf')->get();
        $books = Book::with('project')->get();
        return view('rdr.settings.book.book', compact('books'));
    }

    public function add()
    {
        $categories = Project::pluck('name', 'id');
        $shelves = Shelf::pluck('shelf', 'id');
    
        $datum = date('Y-m-d');
        $nowYear = substr($datum, 0, 4);
        $years = array();
        for ($jahr = 1990; $jahr <= $nowYear; $jahr++) {
            $years[$jahr] = $jahr;
        }
        
        return view('rdr.settings.book.add', compact('categories', 'shelves', 'years'));
    }

    public function store(BookRequest $request)
    {
        $input = $request->all();
        $book = Book::create($input);
        session()->flash('flash_message', 'You have been addded 1 book!');
        return redirect()->route('settings.book');
    }

    public function edit($id)
    {
        $book = Book::findOrFail($id);
        $categories = Project::pluck('name', 'id');
        // $shelves = Shelf::pluck('shelf', 'id');
        
        $datum = date('Y-m-d');
        $nowYear = substr($datum, 0, 4);
        $years = array();
        for ($jahr = 1990; $jahr <= $nowYear; $jahr++) {
            $years[$jahr] = $jahr;
        }
        return view('rdr.settings.book.edit', compact('book', 'categories', 'years'));
        //return view('rdr.settings.book.edit', compact('book', 'categories', 'shelves', 'years'));
    }

    public function update($id, BookRequest $request)
    {
        $book = Book::findOrFail($id);
        $input = $request->all();
        $book->update($input);
        session()->flash('flash_message', 'You have updated 1 book!');
        return redirect()->route('settings.book');
    }

    public function delete($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();
        session()->flash('flash_message', 'You have deleted 1 book!');
        return redirect()->route('settings.book');
    }
}
