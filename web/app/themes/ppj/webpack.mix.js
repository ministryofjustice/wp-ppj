const mix = require('laravel-mix');

mix.setPublicPath('./dest/');

mix.js('src/js/main.js'                , 'dest/js')
  .js('src/js/find-a-job.js'           , 'dest/js')
  .sass('src/sass/landing-page.sass'   , 'dest/css/landing-page.css')
  .sass('src/sass/prison-officer.sass' , 'dest/css/prison-officer.css')
  .sass('src/sass/youth-custody.sass'  , 'dest/css/youth-custody.css')
  .copy('src/img/*'                    , 'dest/img/')
  .copy('src/img/svg/*'                , 'dest/img/svg/')
  .copy('src/img/svg/icons/*'          , 'dest/img/svg/icons/')
;

if (mix.inProduction()) {
    mix.version();
}
else {
    mix.sourceMaps();
}
