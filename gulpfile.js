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
    moduleRoot = 'Module/Shop/';
    jsRoot = moduleRoot + 'UserInterface/assets/ts/',
    jsDist = 'html/assets/js',
    cssRoot = moduleRoot + 'UserInterface/assets/sass/',
    cssDist = 'html/assets/css';
 
gulp.task('sass', function () {
    return gulp.src(cssRoot + "*.scss")
        .pipe(sourcemaps.init())
        .pipe(sass())
        .pipe(sourcemaps.write('./'))
        // .pipe(minCss())
        // .pipe(rename({suffix:'.min'}))
        .pipe(gulp.dest(cssDist));
});

gulp.task('css', function () {
    return gulp.src(cssRoot + "*.css")
        .pipe(minCss())
        .pipe(rename({suffix:'.min'}))
        .pipe(gulp.dest(cssDist));
});

gulp.task('tslint', () =>
    gulp.src(jsRoot + '*.ts')
        .pipe(tslint({
            formatter: 'verbose'
        }))
        .pipe(tslint.report())
);

gulp.task('ts', function () {
    return gulp.src(jsRoot + '*.ts')
    .pipe(tsProject())
    .pipe(uglify())
    .pipe(rename({suffix:'.min'}))
    .pipe(gulp.dest(jsDist));
});

gulp.task('js', function () {
    return gulp.src(jsRoot + '*.js')
    .pipe(uglify())
    .pipe(rename({suffix:'.min'}))
    .pipe(gulp.dest(jsDist));
});

gulp.task('default', ['css', 'js', 'sass', 'tslint', 'ts']);