let mix = require('laravel-mix');

/*pp
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'public/js')
 .sass('resources/assets/sass/app.scss', 'public/css').extract(['vue','element-ui']).version();
// .sass('resources/assets/sass/app.scss', 'public/css');

mix.styles([
    'public/adminLTEcomponents/bootstrap/dist/css/bootstrap.min.css',
    'public/adminLTEcomponents/font-awesome/css/font-awesome.min.css',
    'public/adminLTEcomponents/Ionicons/css/ionicons.min.css',
    'public/adminLTEcomponents/dist/css/AdminLTE.min.css',
    'public/adminLTEcomponents/dist/css/skins/_all-skins.min.css',
    'public/adminLTEcomponents/sweetalert/sweetalert2.min.css',
    // 'public/adminLTEcomponents/element-ui/element-ui.css',
],'public/css/all.css');

mix.scripts([
    'public/adminLTEcomponents/jquery/dist/jquery.min.js',
    'public/adminLTEcomponents/bootstrap/dist/js/bootstrap.min.js',
    'public/adminLTEcomponents/dist/js/adminlte.js',
    'public/adminLTEcomponents/dist/js/demo.js',
    'public/adminLTEcomponents/sweetalert/sweetalert2.min.js',
], 'public/js/all.js');

