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

mix.copy('resources/css/*.css', 'public/css')
    .copy('node_modules/datatables.net/js/jquery.dataTables.min.js', 'resources/js')
    .copy('node_modules/datatables-bulma/js/dataTables.bulma.min.js', 'resources/js')
    .copy('node_modules/datatables.net/js/jquery.dataTables.min.js', 'public/js')
    .copy('node_modules/datatables-bulma/js/dataTables.bulma.min.js', 'public/js')
    .copy('node_modules/datatables.net-select/js/dataTables.select.min.js', 'resources/js')
    .copy('node_modules/datatables.net-select/js/dataTables.select.min.js', 'public/js')
    .copy('node_modules/datatables.net-responsive/js/dataTables.responsive.min.js', 'resources/js')
    .copy('node_modules/datatables.net-responsive/js/dataTables.responsive.min.js', 'public/js')
    .copy('node_modules/@uipath/robot/dist/js/UiPathRobot.js', 'resources/js')
    .copy('node_modules/@uipath/robot/dist/js/UiPathRobot.js', 'public/js')
    .js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .copy('resources/images', 'public/images');
