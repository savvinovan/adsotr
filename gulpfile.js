var gulp        = require('gulp');
var browserSync = require('browser-sync').create();
var reload      = browserSync.reload;
var stylus      = require('gulp-stylus');

gulp.task('browser-sync', function(){
    browserSync.init({
        proxy: {
            target: "http://corp.s-vfu.ru"
        }
    });
    //gulp.watch("./app/stylus/*.styl", ['stylus']);
    gulp.watch("./dev/stylus/*.styl", ['stylus']);
    gulp.watch("./views/site/*.php", reload);
})

gulp.task('default', ['browser-sync'])

gulp.task('stylus', function () {
  return gulp.src('./dev/stylus/*.styl')
    .pipe(stylus({
        compress: true
    }))
    .pipe(gulp.dest('./web/css'))
    .pipe(browserSync.stream());
});
