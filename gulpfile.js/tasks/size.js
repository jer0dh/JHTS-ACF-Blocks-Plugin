const gulp = require('gulp');
const config = require('../config/');

const size = require('gulp-size');


gulp.task('size', function() {

    return gulp.src(config.size.src)
        .pipe(size({showFiles: true}))
        .pipe(gulp.dest( config.destination ));

});
