let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management siehe https://laravel.com/docs/5.5/mix
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
// mix.setPublicPath('../');

// .sass('resources/assets/sass/app1.scss', 'public/css')

mix.js('resources/assets/js/datasetPublish.js', 'public/backend/publish')
    .js('resources/assets/js/app.js', 'public/js')
    .js('resources/assets/js/lib.js', 'public/js')
    .js('resources/assets/js/releaseDataset.js', 'public/backend/publish')
    .scripts([
        'node_modules/datatables.net/js/jquery.dataTables.js',
        'node_modules/datatables.net-buttons/js/dataTables.buttons.js',
        'node_modules/datatables.net-buttons/js/buttons.flash.js',
        'node_modules/datatables.net-buttons/js/buttons.html5.js',
        'node_modules/datatables.net-buttons/js/buttons.print.js',
    ], 'public/js/dataTable.js');
// .options({
//     //publicPath: '../'
//     processCssUrls: false
// });
// mix.copy('node_modules/bootstrap-sass/assets/fonts/bootstrap', 'public/fonts/bootstrap');
