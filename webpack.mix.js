let mix = require('laravel-mix');
let tailwindcss = require('tailwindcss')

mix .setPublicPath('public')
    .sass('./assets/scss/app.scss', './public/css')
    .options({
        processCssUrls: false,
        postCss: [ tailwindcss('./tailwind.config.js') ],
    })
    .js('./assets/js/app.js', './public/js')
    .browserSync("localhost/contact/public/");