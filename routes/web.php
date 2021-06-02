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
$base_domain = config('app.url');
$alias_domain = config('app.alias_url');


Route::group([
    'domain' => 'doi.' . $base_domain,
    'as' => 'doi'
], function () {
    // $dataset_prefix = config('tethys.datacite_prefix');
 

    Route::get('/{dataset_prefix}/tethys.{id}', [
        'as' => 'dataset.show', 'uses' => 'Frontend\PagesController@showDoi',
    ]);

    Route::get('/', function () {
        redirect()->route('frontend.home.index');
    });
});

// Route::group([
//     'domain' => ($base_domain|'www.tethys.at')
// ],
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

//=============================================================================================================
//=================================================file download=============================================
    Route::get('file/download/{id}', [
        'as' => 'file.download', 'uses' => 'Settings\FileController@download',
    ]);

//=================================================publish datasets================================================
    Route::group(
        [
            'namespace' => 'Publish',
            // 'middleware' => ['permission:publish'],
            // 'middleware' => ['role:administrator|reviewer|editor'],
            'prefix' => 'publish',
            'as' => 'publish.',
        ],
        function () {
            Route::get('dataset', [
                'as' => 'dataset.index', 'uses' => 'IndexController@index',
            ]);
            Route::get('dataset/create-step1', [
                'middleware' => ['permission:dataset-submit'],
                'as' => 'dataset.create',
                'uses' => 'IndexController@createStep1',
            ]);
            // Route::post('dataset/store-step1', ['as' => 'dataset.store1', 'uses' => 'IndexController@storeStep1']);

            // Route::get('dataset/create-step2', ['as' => 'dataset.create2', 'uses' => 'IndexController@createStep2']);
            // Route::post('dataset/store-step2', ['as' => 'dataset.store2', 'uses' => 'IndexController@storeStep2']);

            // Route::get('dataset/create-step3', ['as' => 'dataset.create3', 'uses' => 'IndexController@createStep3']);
            Route::post('dataset/store', [
                'middleware' => ['permission:dataset-submit'],
                'as' => 'dataset.store',
                'uses' => 'IndexController@store',
            ]);

            Route::get('workflow/submit/index', [
                'middleware' => ['permission:dataset-list'],
                'as' => 'workflow.submit.index', 'uses' => 'SubmitController@index',
            ]);
            Route::get('workflow/submit/edit/{id}', [
                'middleware' => ['permission:dataset-submit', 'isUserDatasetAdmin:true'],
                'as' => 'workflow.submit.edit', 'uses' => 'SubmitController@edit',
            ]);
            Route::post('workflow/submit/edit/{id}', [
                'middleware' => ['permission:dataset-submit', 'isUserDatasetAdmin:true'],
                'as' => 'workflow.submit.update', 'uses' => 'SubmitController@update',
            ]);
            Route::get('workflow/submit/release/{id}', [
                'middleware' => ['permission:dataset-submit', 'isUserDatasetAdmin:true'],
                'as' => 'workflow.submit.release', 'uses' => 'SubmitController@release',
            ]);
            Route::post('workflow/submit/release/{id}', [
                'middleware' => ['permission:dataset-submit', 'isUserDatasetAdmin:true'],
                'as' => 'workflow.submit.releaseUpdate', 'uses' => 'SubmitController@releaseUpdate',
            ]);
            Route::get('workflow/submit/delete/{id}', [
                'middleware' => ['isUserDatasetAdmin:true'],
                'as' => 'workflow.submit.delete', 'uses' => 'SubmitController@delete',
            ]);
            Route::post('workflow/submit/delete/{id}', [
                'middleware' => ['isUserDatasetAdmin:true'],
                'as' => 'workflow.submit.deleteUpdate', 'uses' => 'SubmitController@deleteUpdate',
            ]);

            //editor
            Route::get('workflow/editor/index', [
                'middleware' => ['permission:dataset-editor-list'],
                'as' => 'workflow.editor.index', 'uses' => 'EditorController@index',
            ]);
            Route::get('workflow/editor/receive/{id}', [
                'middleware' => ['permission:dataset-receive'],
                'as' => 'workflow.editor.receive', 'uses' => 'EditorController@receive',
            ]);
            Route::post('workflow/editor/receive/{id}', [
                'middleware' => ['permission:dataset-receive'],
                'as' => 'workflow.editor.receiveUpdate', 'uses' => 'EditorController@receiveUpdate',
            ]);
            Route::get('workflow/editor/edit/{id}', [
                'middleware' => ['permission:dataset-editor-update'],
                'as' => 'workflow.editor.edit', 'uses' => 'EditorController@edit',
            ]);
            Route::post('workflow/editor/edit/{id}', [
                'middleware' => ['permission:dataset-editor-update'],
                'as' => 'workflow.editor.update', 'uses' => 'EditorController@update',
            ]);
            Route::get('workflow/editor/file/download/{id}', [
                'middleware' => ['permission:dataset-editor-update'],
                'as' => 'workflow.editor.file.download', 'uses' => 'EditorController@download',
            ]);
            Route::get('workflow/editor/approve/{id}', [
                'middleware' => ['permission:dataset-approve'],
                'as' => 'workflow.editor.approve', 'uses' => 'EditorController@approve',
            ]);
            Route::post('workflow/editor/approve/{id}', [
                'middleware' => ['permission:dataset-approve'],
                'as' => 'workflow.editor.approveUpdate', 'uses' => 'EditorController@approveUpdate',
            ]);
            Route::get('workflow/editor/reject/{id}', [
                'middleware' => ['permission:dataset-editor-reject'],
                'as' => 'workflow.editor.reject', 'uses' => 'EditorController@reject',
            ]);
            Route::post('workflow/editor/reject/{id}', [
                'middleware' => ['permission:dataset-editor-reject'],
                'as' => 'workflow.editor.rejectUpdate', 'uses' => 'EditorController@rejectUpdate',
            ]);
            Route::get('workflow/editor/publish/{id}', [
                'middleware' => ['permission:dataset-publish'],
                'as' => 'workflow.editor.publish', 'uses' => 'EditorController@publish',
            ]);
            Route::post('workflow/editor/publish/{id}', [
                'middleware' => ['permission:dataset-publish'],
                'as' => 'workflow.editor.publishUpdate', 'uses' => 'EditorController@publishUpdate',
            ]);
            Route::get('workflow/editor/doi/{id}', [
                'middleware' => ['permission:dataset-publish'],
                'as' => 'workflow.editor.doi', 'uses' => 'EditorController@doiCreate',
            ]);
            Route::post('workflow/editor/doi/{publish_id}', [
                'middleware' => ['permission:dataset-publish'],
                'as' => 'workflow.editor.doiStore', 'uses' => 'EditorController@doiStore',
            ]);

            //doi update list for admin:
            Route::get('workflow/doi/index', [
                'middleware' => ['permission:settings'],
                'as' => 'workflow.doi.index', 'uses' => 'DoiController@index',
            ]);
            Route::get('workflow/doi/edit/{id}', [
                'middleware' => ['permission:settings'],
                'as' => 'workflow.doi.edit', 'uses' => 'DoiController@edit',
            ]);
            Route::post('workflow/doi/update/{publish_id}', [
                'middleware' => ['permission:settings'],
                'as' => 'workflow.doi.update', 'uses' => 'DoiController@update',
            ]);

            //reviewer
            Route::get('workflow/review/index', [
                'middleware' => ['permission:dataset-review-list'],
                'as' => 'workflow.review.index', 'uses' => 'ReviewController@index',
            ]);
            Route::get('workflow/review/{id}', [
                'middleware' => ['permission:dataset-review'],
                'as' => 'workflow.review.review', 'uses' => 'ReviewController@review',
            ]);
            Route::post('workflow/review/{id}', [
                'middleware' => ['permission:dataset-review'],
                'as' => 'workflow.review.reviewUpdate', 'uses' => 'ReviewController@reviewUpdate',
            ]);
            Route::get('workflow/review/reject/{id}', [
                'middleware' => ['permission:dataset-review-reject'],
                'as' => 'workflow.review.reject', 'uses' => 'ReviewController@reject',
            ]);
            Route::post('workflow/review/reject/{id}', [
                'middleware' => ['permission:dataset-review-reject'],
                'as' => 'workflow.review.rejectUpdate', 'uses' => 'ReviewController@rejectUpdate',
            ]);

            //publisher
            Route::get('workflow/publish/index', [
                'middleware' => ['permission:dataset-publish-list'],
                'as' => 'workflow.publish.index', 'uses' => 'PublishController@index',
            ]);
            // Route::get('workflow/publish/{id}', [
            //     'middleware' => ['permission:dataset-publish'],
            //     'as' => 'workflow.publish.publish', 'uses' => 'PublishController@publish',
            // ]);
            // Route::post('workflow/publish/{id}', [
            //     'middleware' => ['permission:dataset-publish'],
            //     'as' => 'workflow.publish.publishUpdate', 'uses' => 'PublishController@publishUpdate',
            // ]);

            Route::get('workflow/changestate/{id}/changestate/{targetState}', [
                'as' => 'review.changestate', 'uses' => 'SubmitController@changestate',
            ]);
        }
    );

//=================================================setting users====================================================
    Route::group(
        [
            'namespace' => 'Settings\Access',
            'prefix' => 'settings/access',
            'as' => 'access.',
        ],
        function () {
            //Route::resource('users','UserController');
            Route::get('user', [
                'as' => 'user.index', 'uses' => 'UserController@index', 'middleware' => ['permission:settings'],
            ]);
            Route::get('user/create', [
                'as' => 'user.create', 'uses' => 'UserController@create', 'middleware' => ['permission:settings'],
            ]);
            Route::post('user/store', [
                'as' => 'user.store', 'uses' => 'UserController@store', 'middleware' => ['permission:settings'],
            ]);
            Route::get('user/edit/{id}', [
                'as' => 'user.edit', 'uses' => 'UserController@edit', 'middleware' => ['auth'],
            ]);
            Route::patch('user/update/{id}', [
                'as' => 'user.update', 'uses' => 'UserController@update', 'middleware' => ['auth'],
            ]);
            Route::get('user/destroy/{id}', [
                'as' => 'user.destroy', 'uses' => 'UserController@destroy', 'middleware' => ['permission:settings'],
            ]);

            //Route::resource('users','RoleController');
            Route::get('role', [
                'as' => 'role.index', 'uses' => 'RoleController@index', 'middleware' => ['permission:settings'],
            ]);
            Route::get('role/create', [
                'as' => 'role.create', 'uses' => 'RoleController@create', 'middleware' => ['permission:settings'],
            ]);
            Route::post('role/store', [
                'as' => 'role.store', 'uses' => 'RoleController@store', 'middleware' => ['permission:settings'],
            ]);
            Route::get('role/edit/{id}', [
                'as' => 'role.edit', 'uses' => 'RoleController@edit', 'middleware' => ['permission:settings'],
            ]);
            Route::patch('role/update/{id}', [
                'as' => 'role.update', 'uses' => 'RoleController@update', 'middleware' => ['permission:settings'],
            ]);
        }
    );

/*
 * CMS Pages Management=============================================================================
 */
    Route::group(
        [
            'middleware' => ['permission:settings'],
            'namespace' => 'Settings',
            'prefix' => 'settings',
            'as' => 'settings.',
        ],
        function () {
            //Route::resource('page', 'PageController', ['except' => ['show', 'update']]);
            Route::get('page', [
                'as' => 'page.index', 'uses' => 'PageController@index',
            ]);
            Route::get('page/edit/{page}', [
                'as' => 'page.edit', 'uses' => 'PageController@edit',
            ]);
            Route::patch('page/update/{id}', [
                'as' => 'page.update', 'uses' => 'PageController@update',
            ]);
            // //For DataTables
            Route::get('pages/get', ['uses' => 'PagesTableController@get'])->name('page.get');
        }
    );

// //=============================================================================================================
// //=================================================setting file=============================================
    Route::get('settings/file/download/{id}', [
        'middleware' => ['isUserFileOwner'],
        'as' => 'settings.file.download', 'uses' => 'Settings\FileController@download',
    ]);

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
        // Route::get('settings/file/download/{id}', [
        //     'as' => 'settings.file.download', 'uses' => 'Settings\DatasetController@download',
        // ]);

        //=================================================setting mimetype=============================================
        Route::get('/settings/mimetype', [
            'as' => 'settings.mimetype.index', 'uses' => 'Settings\MimetypeController@index',
        ]);

        // Route::get('/settings/collection/create', [
        //     'as' => 'settings.collection.create', 'uses' => 'Settings\CollectionController@create',
        // ]);
        // Route::post('settings/collection/store', [
        //     'as' => 'settings.collection.store', 'uses' => 'Settings\CollectionController@store',
        // ]);

        // Route::get('settings/collection/edit/{id}', [
        //     'as' => 'settings.collection.edit', 'uses' => 'Settings\CollectionController@edit',
        // ]);
        Route::patch('settings/mimetype/update', [
            'as' => 'settings.mimetype.update', 'uses' => 'Settings\MimetypeController@update',
        ]);
        // Route::get('settings/collection/show/{collection}', [
        //     'as' => 'settings.collection.show', 'uses' => 'Settings\CollectionController@show',
        // ]);
        // Route::get('settings/collection/delete/{id}', [
        //     'as' => 'settings.collection.delete', 'uses' => 'Settings\CollectionController@delete',
        // ]);
        Route::get('settings/mimetype/down/{id}', [
            'as' => 'settings.mimetype.down', 'uses' => 'Settings\MimetypeController@down',
        ]);
        Route::get('settings/mimetype/up/{id}', [
            'as' => 'settings.mimetype.up', 'uses' => 'Settings\MimetypeController@up',
        ]);

        //=================================================setting collection=============================================
        Route::get('/settings/collection', [
            'as' => 'settings.collection.index', 'uses' => 'Settings\CollectionController@index',
        ]);

        Route::get('/settings/collection/create', [
            'as' => 'settings.collection.create', 'uses' => 'Settings\CollectionController@create',
        ]);
        Route::post('settings/collection/store', [
            'as' => 'settings.collection.store', 'uses' => 'Settings\CollectionController@store',
        ]);

        Route::get('settings/collection/edit/{id}', [
            'as' => 'settings.collection.edit', 'uses' => 'Settings\CollectionController@edit',
        ]);
        Route::patch('settings/collection/edit/{id}', [
            'as' => 'settings.collection.update', 'uses' => 'Settings\CollectionController@update',
        ]);
        Route::get('settings/collection/show/{collection}', [
            'as' => 'settings.collection.show', 'uses' => 'Settings\CollectionController@show',
        ]);
        Route::get('settings/collection/delete/{id}', [
            'as' => 'settings.collection.delete', 'uses' => 'Settings\CollectionController@delete',
        ]);
        //=================================================setting collection_role=========================================
        Route::get('/settings/collectionrole', [
            'as' => 'settings.collectionrole.index', 'uses' => 'Settings\CollectionRoleController@index',
        ]);

        Route::get('settings/collectionrole/edit/{id}', [
            'as' => 'settings.collectionrole.edit', 'uses' => 'Settings\CollectionRoleController@edit',
        ]);
        Route::patch('settings/collectionrole/update/{id}', [
            'as' => 'settings.collectionrole.update', 'uses' => 'Settings\CollectionRoleController@update',
        ]);
        Route::get('settings/collectionrole/show/{collectionrole}', [
            'as' => 'settings.collectionrole.show', 'uses' => 'Settings\CollectionRoleController@show',
        ]);
        Route::get('settings/collectionrole/hide/{id}', [
            'as' => 'settings.collectionrole.hide', 'uses' => 'Settings\CollectionRoleController@hide',
        ]);
        Route::get('settings/collectionrole/up/{id}', [
            'as' => 'settings.collectionrole.up', 'uses' => 'Settings\CollectionRoleController@up',
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
        Route::get('/test', [
            'as' => 'home.index', 'uses' => 'HomeController@test',
        ]);
        Route::get('/', [
            'as' => 'home.index', 'uses' => 'HomeController@index',
        ]);
        Route::get('/contact', [
            'as' => 'home.contact', 'uses' => 'HomeController@contact',
        ]);
        Route::get('/imprint', [
            'as' => 'home.imprint', 'uses' => 'HomeController@imprint',
        ]);
        Route::get('/intro', [
            'as' => 'home.intro', 'uses' => 'HomeController@intro',
        ]);
        Route::get('/news', [
            'as' => 'home.news', 'uses' => 'HomeController@news',
        ]);
        Route::get('/services', [
            'as' => 'home.services', 'uses' => 'HomeController@services',
        ]);
        Route::get('/help', [
            'as' => 'home.help', 'uses' => 'HomeController@help',
        ]);
        Route::get('/info', [
            'as' => 'home.php_info', 'uses' => 'HomeController@phpInfo',
        ]);

        //=================================================Crawlers====================================================
        Route::get('sitelinks', [
            'as' => 'sitelinks.index', 'uses' => 'SitelinkController@index',
        ]);
        Route::get('sitelinks/list/{year}', 'SitelinkController@listDocs')->name('sitelinks.list');

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
        // Route::get('/index', [
        //     'as' => 'search.index', 'uses' => 'SearchController@index',
        // ]);
        Route::post('/queries', [
            'as' => 'queries', 'uses' => 'SearchController@search',
        ]);
        Route::get('/queries/', [
            'as' => 'queries1', 'uses' => 'SearchController@search',
        ]);
        // Route::get('/ping', 'SearchController@ping');
        Route::get('/ping', 'SolariumController@ping');
        // Route::get('/search', 'SolariumController@search');
        Route::get('/search', [
            'as' => 'search.index', 'uses' => 'SolariumController@index',
        ]);
    });

//====================================authentication===========================================================================
// Route::controllers([
// 'auth' => 'Auth\AuthController',
// 'password' => 'Auth\PasswordController',
// ]);
// Auth::routes();
// Authentication Routes...
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
    Route::get('logout', 'Auth\LoginController@logout')->name('logout');

// // Registration Routes...
// Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
// Route::post('register', 'Auth\RegisterController@register');
// // // Password Reset Routes...
// Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
// Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
// Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
// Route::post('password/reset', 'Auth\ResetPasswordController@reset');

    Route::get('refresh-csrf', function () {
        return csrf_token();
    });
};

Route::group(array('domain' => $base_domain), $appRoutes);
if ($alias_domain) {
    Route::group(array('domain' => $alias_domain), $appRoutes);
}
