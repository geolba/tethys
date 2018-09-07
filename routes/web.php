<?php
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
//Auth::routes();
//Route::get('/home', 'HomeController@index')->name('home');

Route::get('/oai', ['as' => 'oai', 'uses' => 'Oai\RequestController@index']);

Route::group(
    [
        'namespace' => 'Publish',
        'middleware' => ['permission:review'],
        'prefix' => 'publish',
        'as' => 'publish.'
    ],
    function () {
        Route::get('dataset/create-step1', ['as' => 'dataset.create', 'uses' => 'IndexController@createStep1']);
        Route::post('dataset/store-step1', ['as' => 'dataset.store1', 'uses' => 'IndexController@storeStep1']);

        Route::get('dataset/create-step2', ['as' => 'dataset.create2', 'uses' => 'IndexController@createStep2']);
        Route::post('dataset/store-step2', ['as' => 'dataset.store2', 'uses' => 'IndexController@storeStep2']);

        Route::get('dataset/create-step3', ['as' => 'dataset.create3', 'uses' => 'IndexController@createStep3']);
        Route::post('dataset/store', ['as' => 'dataset.store', 'uses' => 'IndexController@store']);
    }
);

//=================================================setting users====================================================
Route::group(
    [
        'namespace' => 'Settings\Access',
        'middleware' => ['permission:settings'],
        'prefix' => 'settings/access',
        'as' => 'access.'
    ],
    function () {
        //Route::resource('users','UserController');
        Route::get('user', [
            'as' => 'user.index', 'uses' => 'UserController@index',
        ]);
        Route::get('user/create', [
            'as' => 'user.create', 'uses' => 'UserController@create',
        ]);
        Route::post('user/store', [
            'as' => 'user.store', 'uses' => 'UserController@store',
        ]);
        Route::get('user/edit/{id}', [
            'as' => 'user.edit', 'uses' => 'UserController@edit',
        ]);
        Route::patch('user/update/{id}', [
            'as' => 'user.update', 'uses' => 'UserController@update',
        ]);
        Route::get('user/destroy/{id}', [
            'as' => 'user.destroy', 'uses' => 'UserController@destroy',
        ]);

         //Route::resource('users','RoleController');
        Route::get('role', [
            'as' => 'role.index', 'uses' => 'RoleController@index',
        ]);
        Route::get('role/create', [
            'as' => 'role.create', 'uses' => 'RoleController@create',
        ]);
        Route::post('role/store', [
            'as' => 'role.store', 'uses' => 'RoleController@store',
        ]);
        Route::get('role/edit/{id}', [
            'as' => 'role.edit', 'uses' => 'RoleController@edit',
        ]);
        Route::patch('role/update/{id}', [
            'as' => 'role.update', 'uses' => 'RoleController@update',
        ]);
    }
);

/*
 * CMS Pages Management=============================================================================
 */
Route::group(['namespace' => 'Settings', 'prefix' => 'settings', 'as' => 'settings.',], function () {
    Route::resource('page', 'PageController', ['except' => ['show', 'update']]);

    Route::patch('page/{page}', [
        'as' => 'page.update', 'uses' => 'PageController@update',
    ]);
    // //For DataTables
    Route::get('pages/get', ['uses' => 'PagesTableController@get'])->name('page.get');
});


//=================================================setting home - dashboard=======================================
Route::get('settings/', [
    'as' => 'settings.dashboard', 'uses' => 'Settings\DashboardController@index',
]);

Route::group(['middleware' => ['permission:settings']], function () {

    //=============================================================================================================
    //=================================================setting dataset=============================================
    Route::get('settings/document', [
        'as' => 'settings.document', 'uses' => 'Settings\DatasetController@index',
    ]);
    Route::get('settings/document/{id}', [
        'as' => 'settings.document.show', 'uses' => 'Settings\DatasetController@show',
    ]);
    Route::get('settings/document/edit/{id}', [
        'as' => 'settings.document.edit', 'uses' => 'Settings\DatasetController@edit',
    ]);
    Route::patch('settings/document/update/{id}', [
        'as' => 'settings.document.update', 'uses' => 'Settings\DatasetController@update',
    ]);
    //=================================================setting collection=============================================
    Route::get('/settings/collection', [
        'as' => 'settings.collection', 'uses' => 'Settings\CollectionController@index',
    ]);
    Route::get('settings/collection/edit/{id}', [
        'as' => 'settings.collection.edit', 'uses' => 'Settings\CollectionController@edit',
    ]);
    Route::patch('settings/collection/edit/{id}', [
        'as' => 'settings.collection.update', 'uses' => 'Settings\CollectionController@update',
    ]);
    Route::get('settings/collection/delete/{id}', [
        'as' => 'settings.collection.delete', 'uses' => 'Settings\CollectionController@delete',
    ]);
    //==================================================================================================================
    //=================================================setting project==================================================
    Route::get('/settings/project', [
        'as' => 'settings.project', 'uses' => 'Settings\CategoryController@index',
    ]);
    Route::get('/settings/project/add', [
        'as' => 'settings.project.add', 'uses' => 'Settings\CategoryController@add',
    ]);
    Route::post('settings/project/add', [
        'as' => 'settings.project.post', 'uses' => 'Settings\CategoryController@store',
    ]);
    Route::get('settings/project/edit/{id}', [
        'as' => 'settings.project.edit', 'uses' => 'Settings\CategoryController@edit',
    ]);
    Route::patch('settings/project/edit/{id}', [
        'as' => 'settings.project.update', 'uses' => 'Settings\CategoryController@update',
    ]);
    Route::get('settings/project/delete/{id}', [
        'as' => 'settings.project.delete', 'uses' => 'Settings\CategoryController@delete',
    ]);
    //===================================================================================================
    //=================================================setting person====================================
    Route::get('/settings/person', [
        'as' => 'settings.person', 'uses' => 'Settings\PersonController@index',
    ]);
    Route::get('/settings/person/add', [
        'as' => 'settings.person.add', 'uses' => 'Settings\PersonController@add',
    ]);
    Route::post('settings/person/add', [
        'as' => 'settings.person.post', 'uses' => 'Settings\PersonController@store',
    ]);
    Route::get('settings/person/edit/{id}', [
        'as' => 'settings.person.edit', 'uses' => 'Settings\PersonController@edit',
    ]);
    Route::patch('settings/person/edit/{id}', [
        'as' => 'settings.person.update', 'uses' => 'Settings\PersonController@update',
    ]);
    Route::get('settings/person/delete/{id}', [
        'as' => 'settings.person.delete', 'uses' => 'Settings\PersonController@delete',
    ]);
    Route::get('settings/person/down/{id}', [
        'as' => 'settings.person.down', 'uses' => 'Settings\PersonController@down',
    ]);
    Route::get('settings/person/up/{id}', [
        'as' => 'settings.person.up', 'uses' => 'Settings\PersonController@up',
    ]);
    //=======================================================================================================
    //=================================================setting license=======================================
    Route::get('/settings/license', [
        'as' => 'settings.license', 'uses' => 'Settings\LicenseController@index',
    ]);
    Route::get('settings/license/edit/{id}', [
        'as' => 'settings.license.edit', 'uses' => 'Settings\LicenseController@edit',
    ]);
    Route::patch('settings/license/update/{id}', [
        'as' => 'settings.license.update', 'uses' => 'Settings\LicenseController@update',
    ]);
});

//=================================================home frontend controller=======================================
/*
 * Frontend Routes
 * Namespaces indicate folder structure
 */
Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {
    // includeRouteFiles(__DIR__.'/Frontend/');
    Route::get('/', [
        'as' => 'home.index', 'uses' => 'HomeController@index',
    ]);
    Route::get('/contact', [
        'as' => 'home.contact', 'uses' => 'HomeController@contact',
    ]);
    Route::get('/imprint', [
        'as' => 'home.imprint', 'uses' => 'HomeController@imprint',
    ]);
    Route::get('/about', [
        'as' => 'home.about', 'uses' => 'HomeController@about',
    ]);
    Route::get('/news', [
        'as' => 'home.news', 'uses' => 'HomeController@news',
    ]);

    //=================================================Crawlers====================================================
    Route::get('sitelinks', [
       'as' => 'sitelinks.index', 'uses' => 'SitelinkController@index',
    ]);
    Route::get('sitelinks/list/{year}', 'SitelinkController@list')->name('sitelinks.list');

    Route::get('/dataset', [
        'as' => 'datasets', 'uses' => 'PagesController@datasets',
    ]);
    Route::get('/dataset/{id}', [
        'as' => 'dataset.show', 'uses' => 'PagesController@show',
    ]);

    /*
    * Show pages
    */
    Route::get('pages/{slug}', 'HomeController@showPage')->name('pages.show');

    //=================================================solr search====================================================
    Route::get('/index', [
        'as' => 'search.index', 'uses' => 'SearchController@index',
    ]);
    Route::post('/queries', [
        'as' => 'queries', 'uses' => 'SearchController@search',
    ]);
    Route::get('/queries/', [
        'as' => 'queries1', 'uses' => 'SearchController@search',
    ]);
    Route::get('/ping', 'SearchController@ping');
});

//=================================================borrow====================================================
Route::get('borrow', [
    'as' => 'borrow.borrow', 'uses' => 'BorrowController@index',
]);
Route::post('borrow.post', [
    'as' => 'borrow.post', 'uses' => 'BorrowController@store',
]);
Route::get('laporan', [
    'as' => 'borrow.report', 'uses' => 'BorrowController@report',
]);
Route::get('pengembalian/{id}', [
    'as' => 'borrow.pengembalian', 'uses' => 'BorrowController@pengembalian',
]);
Route::get('perpanjang/{id}', [
    'as' => 'borrow.perpanjang', 'uses' => 'BorrowController@perpanjang',
]);
Route::get('history', [
    'as' => 'borrow.history', 'uses' => 'BorrowController@histori',
]);

//==================================================================================================================
//=================================================setting shelf====================================================
Route::get('/settings/shelf', [
    'as' => 'settings.shelf', 'uses' => 'ShelfController@index',
]);
Route::get('/settings/shelf/add', [
    'as' => 'settings.shelf.add', 'uses' => 'ShelfController@add',
]);
Route::post('settings/shelf/add', [
    'as' => 'settings.shelf.post', 'uses' => 'ShelfController@store',
]);
Route::get('settings/shelf/edit/{id}', [
    'as' => 'settings.shelf.edit', 'uses' => 'ShelfController@edit',
]);
Route::patch('settings/shelf/edit/{id}', [
    'as' => 'settings.shelf.update', 'uses' => 'ShelfController@update',
]);
Route::get('settings/shelf/delete/{id}', [
    'as' => 'settings.shelf.delete', 'uses' => 'ShelfController@delete',
]);

//=========================================================================================================
//=================================================setting periode=========================================
Route::get('/settings/periode', [
    'as' => 'settings.periode', 'uses' => 'PeriodeController@index',
]);
Route::get('settings/periode/edit/{id}', [
    'as' => 'settings.periode.edit', 'uses' => 'PeriodeController@edit',
]);
Route::patch('settings/periode/edit/{id}', [
    'as' => 'settings.periode.update', 'uses' => 'PeriodeController@update',
]);

//=============================================================================================================
//=================================================setting book================================================
Route::get('/settings/book', [
    'as' => 'settings.book', 'uses' => 'BookController@index',
]);
Route::get('/settings/book/add', [
    'as' => 'settings.book.add', 'uses' => 'BookController@add',
]);
Route::post('settings/book/add', [
    'as' => 'settings.book.post', 'uses' => 'BookController@store',
]);
Route::get('settings/book/edit/{id}', [
    'as' => 'settings.book.edit', 'uses' => 'BookController@edit',
]);
Route::patch('settings/book/edit/{id}', [
    'as' => 'settings.book.update', 'uses' => 'BookController@update',
]);
Route::get('settings/book/delete/{id}', [
    'as' => 'settings.book.delete', 'uses' => 'BookController@delete',
]);

//====================================authentication===========================================================================
// Route::controllers([
// 'auth' => 'Auth\AuthController',
// 'password' => 'Auth\PasswordController',
// ]);
//Auth::routes();
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->get('logout', 'Auth\LoginController@logout')->name('logout');
// Registration Routes...
$this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
$this->post('register', 'Auth\RegisterController@register');
// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('password/reset', 'Auth\ResetPasswordController@reset');
