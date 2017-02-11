var gulp = require('gulp'),
autoprefixer = require('gulp-autoprefixer'),
cleanCss = require('gulp-clean-css'),
concat = require('gulp-concat'),
beautify = require('gulp-cssbeautify'),
rename = require('gulp-rename'),
sass = require('gulp-sass'),
sourcemaps = require('gulp-sourcemaps'),
uglify = require('gulp-uglify');

gulp.task('minify:css', function() {
	return gulp.src('core/css/*.scss')
				.pipe(sass())
				.pipe(autoprefixer())
				.pipe(cleanCss())
				.pipe(sourcemaps.init())
				.pipe(sourcemaps.write('maps'))
				.pipe(rename({suffix: ".min"}))
				.pipe(gulp.dest('public/css'));
});

gulp.task('parse:css', function() {
	return gulp.src('core/css/*.scss')
				.pipe(sass())
				.pipe(autoprefixer())
				.pipe(beautify())
				.pipe(gulp.dest('public/css'));
});


gulp.task('build:css', ['minify:css', 'parse:css']);


gulp.task("minify:js", function() {
	return gulp.src('core/js/*.js')
				.pipe(sourcemaps.init())
				.pipe(uglify())
				.pipe(rename({suffix: ".min"}))
				.pipe(sourcemaps.write("maps"))
				.pipe(gulp.dest('public/js'));
});

gulp.task('copy:js', function() {
	return gulp.src('core/js/*.js')
				.pipe(gulp.dest('public/js'));
});

gulp.task('build:js', ['minify:js', 'copy:js']);

gulp.task('watch', function() {
	gulp.watch('core/**/*', ["build:css", 'build:js']);
});


gulp.task('default', ['build:css', 'build:js']);
