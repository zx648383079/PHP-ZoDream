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
    mo = undefined,
    mode = 'dev',
    cssDist = 'html/assets/css',
    maps = {
        doc: 'Document',
        wx: 'WeChat',
        open: 'OpenPlatform',
        tpl: 'Template'
    };
if (process.argv) {
    if (process.argv.indexOf('--prov') > 0 || process.argv.indexOf('--prov=') > 0) {
        mode = 'prov';
    }
    mo = get_env();
    if (mo) {
        // 暂不考虑大小写转化
        switch (mo) {
            case 'gzo':
                moduleRoot = '../zodream/gzo/src/';
                break;
            case 'debugger':
                moduleRoot = '../zodream/debugger/src/';
                break;
            default:
                moduleRoot = 'Module/'+ (maps.hasOwnProperty(mo) ? maps[mo] : mo) +'/';
                break;
        }
        jsRoot = moduleRoot + 'UserInterface/assets/js/';
        tsRoot = moduleRoot + 'UserInterface/assets/ts/';
        cssRoot = moduleRoot + 'UserInterface/assets/sass/';
    }
}

function get_env() {
    if (process.argv.length < 3) {
        return undefined;
    }
    if (process.argv[2].indexOf('--') < 0) {
        return process.argv[2];
    }
    if (process.argv[2].indexOf('=') < 0) {
        return process.argv.length > 3 ? process.argv[3] : undefined;
    }
    var args = process.argv[2].split('=');
    if (args[0] == '--prov') {
        mode = 'prov';
    }
    return args[1];
}

function sassTask() {
    if (mode == 'prov') {
        return gulp.src(cssRoot + "*.scss")
            .pipe(sass({
                sourcemaps: false,
                includePaths: [bourbon, neat]  // 引入其他的
            }))
            .pipe(autoprefixer())
            .pipe(minCss())
            .pipe(gulp.dest(cssDist));
    }
    return gulp.src(cssRoot + "*.scss")
        .pipe(sourcemaps.init())
        .pipe(sass({
            sourcemaps: true,
            includePaths: [bourbon, neat]  // 引入其他的
        }))
        .pipe(autoprefixer())
        .pipe(sourcemaps.write('./'))
        //.pipe(minCss())
        //.pipe(rename({suffix:'.min'}))
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
gulp.task(mo || 'i', build);
gulp.task('default', build);