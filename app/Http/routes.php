<?php
use App\Dataset;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
 */
// Route::get('/api/dropdown/peminjaman/{id}', [
// 'as' => 'api.dropdown.peminjaman', 'uses' => 'PeminjamanController@dropdown'
// ]);
Route::get('/api/dropdown/borrow/{id}', function ($id) {
    if (Request::ajax()) {
        //$category_id = Input::get('category_id');
        // $books = Book::available()->orderByTitle()->where('category_id', '=', $id)->get();
        $books = Dataset::OrderByType()->where('project_id', '=', $id)->get();
        return Response::json($books);
    }
});
