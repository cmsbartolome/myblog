let mix = require('laravel-mix');

mix.copyDirectory('vendor/tinymce/tinymce', 'public/js/tinymce');

mix.webpackConfig({
    output: {
        libraryTarget: 'umd',
    }
})
