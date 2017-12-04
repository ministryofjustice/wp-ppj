const
    mix = require('laravel-mix'),
    dest = 'dest/'
;

mix.js('src/js/main.js'        , dest + 'js')
    .sass('src/sass/main.sass' , dest + 'css')
    .copy('src/img/*'          , dest + 'img/')
    .copy('src/html/*'         , dest + '')
    .copy('src/fonts/*'        , dest + 'fonts')
    .sourceMaps()
    .version()
;

