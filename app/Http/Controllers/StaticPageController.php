<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StaticPageController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function imprint()
    {
        ////$books = Book::available()->orderByTitle()->lists('title', 'id');
        //$persons = Person::active()->orderByName()->pluck('last_name', 'id');
        ////$categories = Category::lists('category', 'id');
        //$categories = Project::get();

        //return view('rdr.borrow.borrow', compact('persons', 'categories'));
    }
}
