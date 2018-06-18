var gulp = require('gulp'),
    minCss = require('gulp-clean-css'),
    sass = require("gulp-sass"),
    sourcemaps = require('gulp-sourcemaps'),
    concat = require('gulp-concat'),
    rename = require('gulp-rename'),
    uglify = require('gulp-uglify'),
    ts = require("gulp-typescript"),
    tslint = require("gulp-tslint"),
    tsProject = ts.createProject('tsconfig.json'),
    moduleRoot = 'Module/Blog/',//'../zodream/gzo/src/',
    jsRoot = moduleRoot + 'UserInterface/assets/js/',
    tsRoot = moduleRoot + 'UserInterface/assets/ts/',
    jsDist = 'html/assets/js',
    cssRoot = moduleRoot + 'UserInterface/assets/sass/',
    cssDist = 'html/assets/css';

function sassTask() {
    return gulp.src(cssRoot + "*.scss")
        .pipe(sourcemaps.init())
        .pipe(sass())
        .pipe(sourcemaps.write('./'))
        // .pipe(minCss())
        // .pipe(rename({suffix:'.min'}))
        .pipe(gulp.dest(cssDist));
}

function cssTask() {
    return gulp.src(cssRoot + "*.css")
        .pipe(minCss())
        .pipe(rename({suffix:'.min'}))
        .pipe(gulp.dest(cssDist));
}

function tslintTask() {
    return gulp.src(jsRoot + '*.ts')
        .pipe(tslint({
            formatter: 'verbose'
        }))
        .pipe(tslint.report());
}

function tsTask() {
    return gulp.src(tsRoot + '*.ts')
    .pipe(tsProject())
    .pipe(uglify())
    .pipe(rename({suffix:'.min'}))
    .pipe(gulp.dest(jsDist));
}

function jsTask() {
    return gulp.src(jsRoot + '*.js')
    .pipe(uglify())
    .pipe(rename({suffix:'.min'}))
    .pipe(gulp.dest(jsDist));
}

exports.sassTask = sassTask;
exports.jsTask = jsTask;
exports.tslintTask = tslintTask;
exports.tsTask = tsTask;
exports.cssTask = cssTask;

var build = gulp.series(gulp.parallel(sassTask, cssTask, tslintTask, tsTask, jsTask));

gulp.task('build', build);
gulp.task('default', build);