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

mix.js('resources/assets/js/lib.js', 'public/js')
.sass('resources/assets/sass/app1.scss', 'public/css')
.options({
    //publicPath: '../'
    processCssUrls: false
});
mix.copy('node_modules/bootstrap-sass/assets/fonts/bootstrap', 'public/fonts/bootstrap');
