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

mix.js('resources/js/datasetPublish.js', 'public/backend/publish')
    .js('resources/js/search/main.ts', 'public/js/search')
    .js('resources/js/app.js', 'public/js')
    .js('resources/js/lib.js', 'public/js')
    .js('resources/js/releaseDataset.js', 'public/backend/publish')
    .js('resources/js/mainEditDataset.js', 'public/backend/publish')
    .js('resources/js/approveDataset.js', 'public/backend/publish')
    .js('resources/js/ckeditor.js', 'public/backend/')   
    .sass('resources/sass/app1.scss', 'public/css', { implementation: require('node-sass')})
    //.sass('node_modules/purecss/build/pure.css', 'public/css', { implementation: require('node-sass') })
    .sass('resources/sass/font-awesome.scss', 'public/css', { implementation: require('node-sass') })
    .js('resources/js/scripts.js', 'public/js')   
    .scripts([
        'node_modules/datatables.net/js/jquery.dataTables.js',
        'node_modules/datatables.net-buttons/js/dataTables.buttons.js',
        'node_modules/datatables.net-buttons/js/buttons.flash.js',
        'node_modules/datatables.net-buttons/js/buttons.html5.js',
        'node_modules/datatables.net-buttons/js/buttons.print.js',
    ], 'public/js/dataTable.js')
    // .sourceMaps()
    .webpackConfig({
        module: {
            rules: [
                // We're registering the TypeScript loader here. It should only
                // apply when we're dealing with a `.ts` or `.tsx` file.
                {
                    test: /\.tsx?$/,
                    loader: 'ts-loader',
                    options: { appendTsSuffixTo: [/\.vue$/] },
                    exclude: /node_modules/,
                },
            ],
        },
       resolve: {
           // We need to register the `.ts` extension so Webpack can resolve
           // TypeScript modules without explicitly providing an extension.
           // The other extensions in this list are identical to the Mix
           // defaults.
           extensions: ['*', '.js', '.jsx', '.vue', '.ts', '.tsx'],
       },
    });
// .options({
//     //publicPath: '../'
//     processCssUrls: false
// });
// mix.copy('node_modules/bootstrap-sass/assets/fonts/bootstrap', 'public/fonts/bootstrap');
