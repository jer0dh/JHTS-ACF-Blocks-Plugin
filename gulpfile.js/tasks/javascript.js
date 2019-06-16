const gulp = require('gulp');
const config = require('../config/');

const sourcemaps = require('gulp-sourcemaps');
const uglify = require('gulp-uglify');
const concat = require('gulp-concat');
const removeCode = require('gulp-remove-code');
const babel = require('gulp-babel');
const rename = require('gulp-rename');
const filter = require('gulp-filter');
const filesExist = require('files-exist');


const deployRemote = require('../lib/deployRemote');
const gutil = require('gulp-util');
/**
 * Vendor Scripts & Assets
 * copy all vendor js and assets to destination excluding any js that is in pkg.jsConcatenatedScripts or pkg.jsConcatenatedVendorScripts
 */
gulp.task('js-vendor-scripts-assets', function(){
    return gulp.src([config.srcFolder + '/js/vendor/**/*'].concat( config.negatedAllConcatenatedScripts ))
        .pipe(gulp.dest( config.destination + '/js/vendor/'));
});



/**
 * copy all other js scripts and assets to destination excluding any js that is in pkg.jsConcatentatedScripts and excluding anything under vendor or app folder
 */
gulp.task('js-other-scripts-assets', function() {
    return gulp.src([config.srcFolder + '/js/**/*', '!' + config.srcFolder + '/js/vendor/**/*', '!' + config.jsAppPath +'/**/*'].concat( config.negatedAllConcatenatedScripts ).concat( config. negatedAllConcatenatedAdminScripts ))
        .pipe(gulp.dest( config.destination + '/js/'));
});


/**
 * create and copy to destination a minified js
 */
gulp.task('js-other-scripts-minify', function() {
    return gulp.src([config.srcFolder + '/js/**/*.js', '!' + config.srcFolder + '/js/vendor/**/*', '!' + config.srcFolder + '/js/**/*.min.js', '!' + config.jsAppPath +'/**/*'].concat( config.negatedAllConcatenatedScripts ).concat( config.negatedAllConcatenatedAdminScripts ))
        .pipe(sourcemaps.init())
        .pipe(removeCode(config.removeCodeOptions))
        .pipe(babel({ presets: ['env']}))
        .pipe(rename({extname: '.min.js'}))
        .pipe(uglify())
        .on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest( config.destination + '/js/'));
});

/**
 * Create Main Concatenated Script file
 *
 * Take array in package.json of js files with paths, run babel, concat, and minify into a file named by jsConcatenatedScriptsName in package.json
 */


gulp.task('js-concat-scripts', function(cb) {
    const noVendorFilter = filter(config.negatedConcatenatedVendorScripts.concat(config.concatenatedScripts), {restore: true});  //only script files and remove vendor scripts temporarily while babel is run

    gulp.src(filesExist(config.allConcatenatedScripts))
        .pipe(sourcemaps.init())
        .pipe(removeCode(config.removeCodeOptions))
        .pipe(noVendorFilter)
        .pipe(babel({ presets: ['env']}))
        .pipe(noVendorFilter.restore)
        .pipe(concat(config.jsConcatenatedScriptsName))
        .pipe(gulp.dest( config.destination + '/js' ))
        .pipe(rename({extname: '.min.js'}))
        .pipe(uglify())
        .on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest( config.destination + '/js'));

    const noAdminVendorFilter = filter(config.negatedConcatenatedAdminVendorScripts.concat(config.concatenatedAdminScripts).concat(config.srcFolder + '/template-parts/blocks/**/*.js'), {restore: true});  //only script files and remove vendor scripts temporarily while babel is run

    //Add all the admin scripts and block scripts into a single file.
    gulp.src(filesExist(config.allConcatenatedAdminScripts).concat(config.srcFolder + '/template-parts/blocks/**/*.js'))
        .pipe(sourcemaps.init())
        .pipe(removeCode(config.removeCodeOptions))
        .pipe(noAdminVendorFilter)
        .pipe(babel({ presets: ['env']}))
        .pipe(noAdminVendorFilter.restore)
        .pipe(concat(config.jsConcatenatedAdminScriptsName))
        .pipe(gulp.dest( config.destination + '/js' ))
        .pipe(rename({extname: '.min.js'}))
        .pipe(uglify())
        .on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest( config.destination + '/js'));


    cb();
});



gulp.task('js-clean', function(cb) {
    rimraf( config.destination + '/js', cb );
});



gulp.task('js-deploy', function() {

    return deployRemote( config.destination + '/js/**/*', '/js' );

});
