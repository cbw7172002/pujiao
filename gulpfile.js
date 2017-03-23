/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */
/*
 var elixir = require('laravel-elixir');

 elixir(function(mix) {
     mix.sass('app.scss');
 });
 */
var gulp = require('gulp');
var jshint = require('gulp-jshint');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var livereload = require('gulp-livereload');
var minifyHtml = require('gulp-minify-html');

gulp.task('jshint', function () {
	// return gulp.src('public/home/js/avalonController/lessonComment/*/*.js').pipe(jshint()).pipe(jshint.reporter('default')).pipe(livereload());
	return gulp.src('public/home/js/avalonController/evaluateManageTea/editPaper.js').pipe(jshint()).pipe(jshint.reporter('default')).pipe(livereload());
});

gulp.task('minify', function () {
	// 合并 & 压缩
	return gulp.src('public/home/js/avalonController/evaluateManageTea/editPaper.js').pipe(concat('gulpTest.js')).pipe(gulp.dest('public/home/js/avalonController/evaluateManageTea'))
		.pipe(uglify()).pipe(rename('gulpTest.min.js')).pipe(gulp.dest('public/home/js/avalonController/evaluateManageTea')).pipe(livereload());
});

gulp.task('watch', function () {
	gulp.watch('public/home/js/avalonController/evaluateManageTea/editPaper.js', ['jshint', 'minify']);
});

// gulp.task('minify-html', function () {
// 	return gulp.src('resources/views/home/lessonComment/studentHomePage/index.html').pipe(minifyHtml())
// 		.pipe(rename('indexs.html')).pipe(gulp.dest('resources/views/home/lessonComment/studentHomePage'));
// });

gulp.task('default', ['jshint', 'watch', 'minify']);
