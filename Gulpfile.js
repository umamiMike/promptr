var gulp = require('gulp');
var sass = require('gulp-sass');


var input = 'web/css/scss/*.scss';
var output = 'web/css/styles.css';



var sassOptions = {
  errLogToConsole: true,
  outputStyle: 'expanded'
};

gulp.task('sass', function () {
  return gulp
    .src(input)
    .pipe(sass(sassOptions).on('error', sass.logError))
    .pipe(gulp.dest('web/css/styles.css'));
});
