var gulp = require('gulp'),
    gulpAddSrc = require('gulp-add-src'),
    gulpAppendSrc = gulpAddSrc.append,
    gulpConcat = require('gulp-concat'),
    gulpCssmin = require('gulp-cssmin'),
    gulpFilter = require('gulp-filter'),
    gulpLess = require('gulp-less'),
    gulpPlumber = require('gulp-plumber'),
    gulpPrepend = gulpAddSrc.prepend,
    gulpSass = require('gulp-sass'),
    gulpUglify = require('gulp-uglify');

gulp.task('css', function() {
    var sass;
    return gulp.src('./assets/less/app.less')
        .pipe(gulpPlumber())
        .pipe(gulpLess())
        .pipe(gulpAppendSrc([
            './bower_components/datatables-responsive/css/responsive.bootstrap.scss'
        ]))
        .pipe(sass = gulpFilter(['**/*.scss'], {restore: true}))
        .pipe(gulpSass())
        .pipe(sass.restore)
        .pipe(gulpCssmin({
            keepSpecialComments: 0
        }))
        .pipe(gulpAppendSrc([
            './bower_components/font-awesome/css/font-awesome.min.css',
            './bower_components/datatables/media/css/dataTables.bootstrap.min.css'
        ]))
        .pipe(gulpConcat('app.css'))
        .pipe(gulp.dest('./www/css'));
});

gulp.task('default', ['css', 'fonts', 'js']);

gulp.task('fonts', function() {
    var files = [
        './bower_components/bootstrap/fonts/*.{eot,svg,ttf,woff,woff2}',
        './bower_components/font-awesome/fonts/*.{eot,otf,svg,ttf,woff,woff2}'
    ];
    return gulp.src(files)
        .pipe(gulpPlumber())
        .pipe(gulp.dest('./www/fonts'));
});

gulp.task('js', function() {
    return gulp.src('./assets/js/app.js')
        .pipe(gulpPlumber())
        .pipe(gulpPrepend([
            './bower_components/bootbox.js/bootbox.js'
        ]))
        .pipe(gulpUglify())
        .pipe(gulpPrepend([
            './bower_components/jquery/dist/jquery.min.js',
            './bower_components/bootstrap/dist/js/bootstrap.min.js',
            './bower_components/bootstrap-growl/jquery.bootstrap-growl.min.js',
            './bower_components/datatables/media/js/jquery.dataTables.min.js',
            './bower_components/datatables/media/js/dataTables.bootstrap.min.js',
            './bower_components/datatables-responsive/js/dataTables.responsive.js',
            './bower_components/typeahead.js/dist/typeahead.bundle.min.js',
            './bower_components/handlebars/handlebars.min.js'
        ]))
        .pipe(gulpConcat('app.js'))
        .pipe(gulp.dest('./www/js'));
});
