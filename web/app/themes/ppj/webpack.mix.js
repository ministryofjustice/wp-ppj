const mix = require('laravel-mix');
const dest = '../web/app/themes/ppj/';
//const dest = 'public/';

mix.js('src/js/main.js'       , dest + 'js')
    .sass('src/sass/main.sass', dest + 'css')
    .copy('src/img/*'         , dest + 'img/')
    .copy('src/html/*'        , dest + '')
    .copy('src/fonts/*'       , dest + 'fonts')
    .sourceMaps()
;

// mix.browserSync({
//     proxy: 'localhost',
//     port: 8890
// });

if (process.env.MIX_HEROKU) {
    console.log('MIX_HEROKU is true!');
    mix.copy('temp-auth/.htaccess', 'public/.htaccess');
} else {
    console.log('MIX_HEROKU is false!');
}
