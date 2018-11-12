var gulp = require('gulp'),
    minCss = require('gulp-clean-css'),
    sass = require("gulp-sass"),
    sourcemaps = require('gulp-sourcemaps'),
    concat = require('gulp-concat'),
    rename = require('gulp-rename'),
    uglify = require('gulp-uglify'),
    //@import "bourbon";
    bourbon    = require("bourbon").includePaths,
    // @import "neat";
    neat       = require("bourbon-neat").includePaths,
    autoprefixer = require('gulp-autoprefixer'),
    ts = require("gulp-typescript"),
    tslint = require("gulp-tslint"),
    tsProject = ts.createProject('tsconfig.json'),
    moduleRoot = '',
    jsRoot = moduleRoot + 'UserInterface/assets/js/',
    tsRoot = moduleRoot + 'UserInterface/assets/ts/',
    cssRoot = moduleRoot + 'UserInterface/assets/sass/',
    jsDist = 'html/assets/js',
    moudule = undefined,
    cssDist = 'html/assets/css';

if (process.argv && process.argv.length > 2) {
    moudule = process.argv[2];
    // 暂不考虑大小写转化
    switch (moudule) {
        case 'gzo':
            moduleRoot = '../zodream/gzo/src/';
            break;
        case 'debugger':
            moduleRoot = '../zodream/debugger/src/';
            break;
        default:
            moduleRoot = 'Module/'+ moudule +'/';
            break;
    }
    jsRoot = moduleRoot + 'UserInterface/assets/js/';
    tsRoot = moduleRoot + 'UserInterface/assets/ts/';
    cssRoot = moduleRoot + 'UserInterface/assets/sass/';
}


function sassTask() {
    return gulp.src(cssRoot + "*.scss")
        .pipe(sourcemaps.init())
        .pipe(sass({
            sourcemaps: true,
            includePaths: [bourbon, neat]  // 引入其他的
        }))
        .pipe(autoprefixer())
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
gulp.task(moudule || 'i', build);
gulp.task('default', build);