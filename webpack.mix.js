const mix = require('laravel-mix');

mix.js('src/js/main.js', 'public/js')
    .sourceMaps()
    .sass('src/sass/main.sass', 'public/css');

// mix.browserSync({
//     proxy: 'localhost',
//     port: 8890
// });
