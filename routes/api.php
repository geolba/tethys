<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Person;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
$base_domain = config('app.url');
$alias_domain = config('app.alias_url');

// Route::domain('tethys.at')->group(function () {
$appRoutes = function () {
    
    Route::match(array('GET', 'POST'), '/oai', ['as' => 'oai', 'uses' => 'Oai\RequestController@index']);

    //Route::get('/', 'LocalizationController@index');
        Route::get(
            'setlocale/{lang}',
            [
                'as' => 'setlocale', //name()
                'uses' => 'Frontend\LocalizationController@setLocale',
            ]
        );
    
    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::get('/oai', 'Oai\RequestController@identify');
    
    Route::get('/api/persons', function () {
        $request = request();
        //$query = Person::query();//->with('group');
        $query = Person::where('status', true);
        
        // handle sort option
        //if (request()->has('sort')) {
        if (null !== ($request->input('sort'))) {
            // handle multisort
            $sorts = explode(',', request()->sort);
            foreach ($sorts as $sort) {
                list($sortCol, $sortDir) = explode('|', $sort);
                $query = $query->orderBy($sortCol, $sortDir);
            }
        } else {
            $query = $query->orderBy('id', 'asc');
        }
    
        //handle filter
        if ($request->exists('filter')) {
            $query->where(function ($q) use ($request) {
                $value = "%{$request->filter}%";
                $q->where('first_name', 'like', $value)
                    ->orWhere('last_name', 'like', $value)
                    ->orWhere('email', 'like', $value);
            });
        }
        $perPage = request()->has('per_page') ? (int) request()->per_page : null;
        // $pagination = $query->with('address')->paginate($perPage);
        $pagination = $query->paginate($perPage);
        // $pagination = $query->get();
        $pagination->appends([
            'sort' => request()->sort,
            'filter' => request()->filter,
            'per_page' => request()->per_page
        ]);
        // The headers 'Access-Control-Allow-Origin' and 'Access-Control-Allow-Methods'
        // are to allow you to call this from any domain (see CORS for more info).
        // This is for local testing only. You should not do this in production server,
        // unless you know what it means.
        return response()
            ->json($pagination)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET');
    });
};

Route::group(array('domain' => $base_domain), $appRoutes);
if ($alias_domain) {
    Route::group(array('domain' => $alias_domain), $appRoutes);
}
