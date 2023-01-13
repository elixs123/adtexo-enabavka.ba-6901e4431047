// Pozivanje Gulp-a
var gulp = require('gulp');
var sass = require('gulp-sass');
// Definicija glavnih foldera (prema defaultnoj Laravel strukturi foldera)
var src = 'resources/assets/';
var dest = 'public/assets/theme/';
var fontName = 'icons';
// Automatsko pozivanje svih plugina
var plugins = require("gulp-load-plugins")({
    pattern: ['gulp-*', 'gulp.*'],
    replaceString: /\bgulp[\-.]/
});

// Minifikacija JS-a
gulp.task('scripts', function() {
    return gulp.src([src + 'js/*.js', src + 'js/pickadate/*.js'])
        .pipe(plugins.concat('app.js'))
        .pipe(plugins.rename({suffix: '.min'}))
        .pipe(plugins.uglify())
        .pipe(gulp.dest(dest + 'js'));
});

// Compile CSS from SASS
gulp.task('sass', function() {
    return gulp.src(src + 'sass/app.scss')
        .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
        .pipe(plugins.rename({suffix: '.min'}))
        .pipe(gulp.dest(dest + 'css'));
});

// Prati izmjene u slijedećim folderima i pokreni odgovarajući task
gulp.task('watch', function() {
    gulp.watch(src + 'js/*.js', ['scripts']);
    gulp.watch(src + 'sass/*.scss', ['sass']);
});

// Glavni Task
gulp.task('default', ['scripts', 'sass', 'watch']);
