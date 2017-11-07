const mix = require('laravel-mix');

mix.js('src/js/main.js', 'public/js')
    .sourceMaps()
    .sass('src/sass/main.sass', 'public/css')
    .copy('src/img/*', 'public/img/')
    .copy('src/html/*', 'public/')
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
