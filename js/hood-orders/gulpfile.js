var gulp = require('gulp');
var react = require('gulp-react');
var babel = require('gulp-babel');
var open = require('gulp-open');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var pump = require('pump');
 
gulp.task('compress', ['babel'], function (cb) {
  pump([
        gulp.src('index.js'),
        uglify(),
        gulp.dest('./')
    ],
    cb
  );
});

gulp.task('jsx', function () {
    return gulp.src(['components/*.jsx','index.jsx'])
        .pipe(react())
        .pipe(gulp.dest('dist'));
});

gulp.task('babel', function () {
    return gulp.src('index.js')
        .pipe(babel())
        .pipe(gulp.dest('./'));
});

gulp.task('concat', ['jsx'], function() {
	return gulp.src('./dist/*.js')
    	.pipe(concat('index.js'))
    	.pipe(gulp.dest('./'));
});

gulp.task('watch', function() {
  // Наблюдение за jsx файлами
  gulp.watch(['components/*.jsx','index.jsx'], ['concat']); 
});


gulp.task('default', ['watch']);

