'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var gulpIf = require('gulp-if');
var del = require('del');
var plumber = require('gulp-plumber');
var postcss = require('gulp-postcss');
var autoprefixer = require ('autoprefixer');
var minify = require('gulp-csso');
var rename = require('gulp-rename');
var browserSync = require('browser-sync').create();
var imagemin = require('gulp-imagemin');

var isDevelopment = !process.env.NODE_ENV || process.env.NODE_ENV == 'development';

gulp.task('styles', function() {

  return gulp.src('frontend/sass/style.sass')
  .pipe(gulpIf(isDevelopment, sourcemaps.init()))
  .pipe(plumber())
  .pipe(sass())
  .pipe(postcss([
    autoprefixer()
    ]))
  .pipe(gulpIf(isDevelopment, sourcemaps.write()))
  .pipe(gulp.dest('public/css'))
  .pipe(minify())
  .pipe(rename('style.min.css'))
  .pipe(gulp.dest('public/css'));

});

gulp.task('images', function() {
  return gulp.src('frontend/assets/img/*.{png,jpg,svg}')
  .pipe(imagemin([
    imagemin.optipng({optimizationLevel: 3}),
    imagemin.jpegtran({progressive: true}),
    imagemin.svgo()
    ]))
  .pipe(gulp.dest('frontend/assets/img'));
})

gulp.task('assets', function() {
  return gulp.src('frontend/assets/**')
  .pipe(gulp.dest('public'));
})

gulp.task('clean', function() {
  return del('public');
});

gulp.task('build', gulp.series('clean', 'images', gulp.parallel('styles', 'assets')));

gulp.task('watch', function() {
  gulp.watch('frontend/sass/**/*.*', gulp.series('styles'));
  gulp.watch('frontend/assets/**/*.*', gulp.series('assets'));
})

gulp.task('serve', function() {
  browserSync.init({
    server: 'public'
  });
  browserSync.watch('public/**/*.*').on('change', browserSync.reload);
}); 

gulp.task('dev', gulp.series('build', gulp.parallel('watch', 'serve')));