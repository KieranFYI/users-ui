const mix = require('laravel-mix');
const path = require("path");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
    .setPublicPath('public')
    /* JS */
    .js('resources/js/vue.js', 'vue.js')
    .js('resources/js/vue-users-tabs-logging.js', 'vue-users-tabs-logging.js')
    .vue()

    /* Options */
    .options({
        processCssUrls: false
    })
    .disableNotifications()
    .version();
