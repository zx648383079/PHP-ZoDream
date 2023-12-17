var loader = require('gulp-vue2mini').PackLoader,
    //@import "bourbon";
    bourbon    = require('bourbon').includePaths,
    // @import "neat";
    neat       = require('bourbon-neat').includePaths,
    sassIncludes = [].concat(bourbon, neat),
    jsDist = 'html/assets/js/',
    cssDist = 'html/assets/css/',
    moduleMaps = {
        doc: 'Document',
        wx: 'WeChat',
        open: 'OpenPlatform',
        tpl: 'Template'
    };
function loadFolder(module) {
    if (!module || module === 'default' || module === 'all') {
        return 'UserInterface/';
    }
    var prefix = '';
    var moduleRoot = '';
    var modu = module;
    if (module.indexOf('Game-') === 0) {
        modu = module.replace('-', '/');
    } else if (module.indexOf('-') > 0) {
        [modu, prefix] = module.split('-');
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
            moduleRoot = 'Module/'+ (moduleMaps.hasOwnProperty(modu) ? moduleMaps[modu] : modu) +'/';
            break;
    }
    if (prefix && prefix.length > 0) {
        prefix += '/';
    }
    return moduleRoot + 'UserInterface/' + prefix;
}

var taskName = loader.taskName || 'default';

async function folderTask(root) {
    await loader.input(root + 'assets/ts/*.ts')
    .ts('tsconfig.json', !loader.argv.min)
    .output(jsDist);
    await loader.input(root + 'assets/sass/*.scss')
    .sass({
        sourcemaps: !loader.argv.min,
        includePaths: taskName === 'default' ? sassIncludes : ['UserInterface/assets/sass/'].concat(sassIncludes)  // 引入其他的
    }).output(cssDist);
    await loader.input(root + 'assets/js/*.js')
    .output(jsDist);
} 

loader.task(taskName, async () => {
    if (taskName !== 'all') {
        var root = loadFolder(taskName);
        await folderTask(root);
        return;
    }
    var folderItems = [
        '',
        'Auth',
        'Blog',
        'Book',
        'Chat',
        'CMS',
        'CMS-default',
        'Contact',
        'Counter',
        'Disk',
        'Document',
        'Exam',
        'Family',
        'Finance',
        'Forum',
        'Legwork',
        'LogView',
        'MicroBlog',
        'Navigation',
        'Note',
        'OpenPlatform',
        'ResourceStore',
        'SEO',
        'Shop',
        'Short',
        'Task',
        'Template',
        'Tool',
        'WeChat',
        'gzo',
        'debugger',
    ];
    for (var i = 0; i < folderItems.length; i++) {
        await folderTask(loadFolder(folderItems[i]));
    }
});