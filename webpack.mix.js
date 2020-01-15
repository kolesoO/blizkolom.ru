let mix = require('laravel-mix');

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

mix
    .js('resources/assets/js/public/mobile/app.js', 'public/js/public/mobile')
    .js('resources/assets/js/public/desktop/app.js', 'public/js/public/desktop')
    .styles(
        [
            "resources/assets/css/public/mobile/main.css"
        ],
        'public/css/public/mobile/app.css'
    )
    .styles(
        [
            "resources/assets/css/public/mobile/after.css"
        ],
        'public/css/public/mobile/app-after.css'
    )
    .styles(
        [
            "resources/assets/css/public/desktop/main.css"
        ],
        'public/css/public/desktop/app.css'
    )
    .styles(
        [
            "resources/assets/css/public/desktop/after.css"
        ],
        'public/css/public/desktop/app-after.css'
    )
;
