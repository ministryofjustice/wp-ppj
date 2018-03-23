const mix = require('laravel-mix');

mix.setPublicPath('./dest/');

mix.js('src/js/main.js'        , 'dest/js')
    .sass('src/sass/main.sass' , 'dest/css')
    .sass('src/sass/error-page.sass', 'dest/css')
    .copy('src/img/*'          , 'dest/img/')
    .copy('src/img/svg/*'      , 'dest/img/svg/')
;

if (mix.inProduction()) {
    mix.version();
}
else {
    mix.sourceMaps();
}
