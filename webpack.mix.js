const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

/** JS **/
mix.setPublicPath('www')
    .copy([
            'node_modules/jquery/dist/jquery.min.js',
            'node_modules/vue/dist/vue.min.js',
            'node_modules/vue/dist/vue.js',
            'node_modules/chart.js/dist/Chart.min.js',
            'node_modules/@ckeditor/ckeditor5-build-classic/build/ckeditor.js'
        ],
        'www/js/lib')


/** CSS **/
    .sass('resources/scss/app.scss', 'www/css')
    .sourceMaps(true, 'source-map')
    .options({
        processCssUrls: false
    })
    .minify('www/css/app.css'); // by default, this will only happen if build is production -> npm run production
