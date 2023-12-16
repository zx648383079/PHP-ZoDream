var loader = require("gulp-vue2mini").PackLoader,
    //@import "bourbon";
    bourbon    = require("bourbon").includePaths,
    // @import "neat";
    neat       = require("bourbon-neat").includePaths,
    moduleRoot = '',
    jsRoot = moduleRoot + 'UserInterface/assets/js/',
    tsRoot = moduleRoot + 'UserInterface/assets/ts/',
    cssRoot = moduleRoot + 'UserInterface/assets/sass/',
    sassIncludes = [cssRoot].concat(bourbon, neat),
    jsDist = 'html/assets/js/',
    mo = undefined,
    cssDist = 'html/assets/css/',
    maps = {
        doc: 'Document',
        wx: 'WeChat',
        open: 'OpenPlatform',
        tpl: 'Template'
    };
mo = loader.taskName;
if (!mo && mo !== 'default') {
    var prefix = '';
    var modu = mo;
    if (mo.indexOf('Game-') === 0) {
        modu = mo.replace('-', '/');
    } else if (mo.indexOf('-') > 0) {
        [modu, prefix] = mo.split('-');
    }
    // 暂不考虑大小写转化
    switch (modu) {
        case 'gzo':
            moduleRoot = '../zodream/gzo/src/';
            break;
        case 'debugger':
            moduleRoot = '../zodream/debugger/src/';
            break;
        default:
            moduleRoot = 'Module/'+ (maps.hasOwnProperty(modu) ? maps[modu] : modu) +'/';
            break;
    }
    if (prefix && prefix.length > 0) {
        prefix += '/';
    }
    var baseRoot = moduleRoot + 'UserInterface/' + prefix;
    jsRoot = baseRoot + 'assets/js/';
    tsRoot = baseRoot + 'assets/ts/';
    cssRoot = baseRoot + 'assets/sass/';
}

loader.task(loader.taskName, async () => {
    await loader.input(tsRoot + '*.ts')
    .ts('tsconfig.json', !loader.argv.min)
    .output(jsDist);
    await loader.input(cssRoot + "*.scss")
    .sass({
        sourcemaps: !loader.argv.min,
        includePaths: sassIncludes  // 引入其他的
    }).output(cssDist);
    await loader.input(jsRoot + '*.js')
    .output(jsDist);
});