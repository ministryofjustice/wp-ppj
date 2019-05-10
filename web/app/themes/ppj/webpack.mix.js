const mix = require('laravel-mix');

mix.setPublicPath('./dist/');

mix.js('src/js/main.js'                , 'dist/js')
  .js('src/js/find-a-job.js'           , 'dist/js')
  .sass('src/sass/landing-page.sass'   , 'dist/css/landing-page.css')
  .sass('src/sass/prison-officer.sass' , 'dist/css/prison-officer.css')
  .sass('src/sass/youth-custody.sass'  , 'dist/css/youth-custody.css')
  .sass('src/sass/error-page.sass'     , 'dist/css/error-page.css')
  .copy('src/img/*'                    , 'dist/img/')
  /*.copy('src/img/icons/!*'              , 'dist/img/icons/')*/
  .copy('src/img/svg/*'                , 'dist/img/svg/')
  .copy('src/img/svg/icons/*'          , 'dist/img/svg/icons/')
;

if (mix.inProduction()) {
    mix.version();
}
else {
    mix.sourceMaps();
}
