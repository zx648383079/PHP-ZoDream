;
define(["jquery", "bui"], function () {
    BUI.config({
        alias: {
            'common': 'assets/js/bui/common',
            'module': 'assets/js/bui/module'
        }
    });
    BUI.use('common/main', function () {
        var config = [{
                id: 'menu',
                homePage: 'dashboard',
                menu: [{
                        text: '首页内容',
                        items: [
                            { id: 'dashboard', text: '面板', href: 'admin.php/home/main', closeable: false }
                        ]
                    }, {
                        text: '系统设置',
                        items: [
                            { id: 'option', text: '系统参数设置', href: 'admin.php/option' },
                            { id: 'link', text: '友情链接', href: 'admin.php/link' }
                        ]
                    }, {
                        text: '文件结构',
                        items: [
                            { id: 'resource', text: '资源文件结构', href: 'admin.php/resource' }
                        ]
                    }]
            }, {
                id: 'duty',
                menu: [{
                        text: '导航',
                        items: [
                            { id: 'kind', text: '分类', href: 'admin.php/navigation/category' },
                            { id: 'url', text: '网址', href: 'admin.php/navigation' }
                        ]
                    }, {
                        text: '博客管理',
                        items: [
                            { id: 'blog', text: '页面', href: 'admin.php/post' },
                            { id: 'term', text: '分类', href: 'admin.php/post/term' },
                            { id: 'comment', text: '评论', href: 'admin.php/post/comment' }
                        ]
                    }, {
                        text: '论坛管理',
                        items: [
                            { id: 'forum', text: '版块', href: 'admin.php/forum' },
                            { id: 'thread', text: '主题', href: 'admin.php/forum/thread' },
                            { id: 'post', text: '帖子', href: 'admin.php/forum/post' }
                        ]
                    }, {
                        text: '随想管理',
                        items: [
                            { id: 'talk', text: '随想', href: 'admin.php/talk' }
                        ]
                    }, {
                        text: '废料科普管理',
                        items: [
                            { id: 'waste', text: '废料分类', href: 'admin.php/waste' },
                            { id: 'company', text: '公司管理', href: 'admin.php/company' }
                        ]
                    }]
            }, {
                id: 'plugin',
                menu: [{
                        text: '微信管理',
                        items: [
                            { id: 'wechat', text: '账号管理', href: 'admin.php/wechat' },
                            { id: 'reply', text: '自动回复', href: 'admin.php/wechat/reply' },
                            { id: 'a', text: '群发功能', href: 'admin.php/wechat' },
                            { id: 'b', text: '自定义菜单', href: 'admin.php/wechat/reply' },
                            { id: 'c', text: '投票管理', href: 'admin.php/wechat' },
                            { id: 'd', text: '消息管理', href: 'admin.php/wechat/reply' },
                            { id: 'e', text: '用户管理', href: 'admin.php/wechat/reply' },
                            { id: 'f', text: '素材管理', href: 'admin.php/wechat' },
                            { id: 'g', text: '微信商城', href: 'admin.php/wechat/reply' },
                        ]
                    }, {
                        text: '更多示例',
                        items: [
                            { id: 'tab', text: '使用tab过滤', href: 'search/tab.html' }
                        ]
                    }]
            }, {
                id: 'user',
                menu: [{
                        text: '用户管理',
                        items: [
                            { id: 'index', text: '修改个人资料', href: 'admin.php/user' },
                            { id: 'role', text: '管理角色', href: 'admin.php/user/role' },
                            { id: 'authorization', text: '管理权限', href: 'admin.php/user/authorization' },
                            { id: 'user', text: '管理用户', href: 'admin.php/user/user' },
                            { id: 'loginLog', text: '管理登陆日志', href: 'admin.php/user/loginLog' },
                            { id: 'log', text: '管理操作日志', href: 'admin.php/user/log' }
                        ]
                    }, {
                        text: '私信管理',
                        items: [
                            { id: 'message', text: '所有私信', href: 'admin.php/message' },
                            { id: 'all', text: '群发消息', href: 'admin.php/message/all' }
                        ]
                    }]
            }, {
                id: 'model',
                menu: [{
                        text: '备份与恢复数据',
                        items: [
                            { id: 'backup', text: '备份数据', href: 'admin.php/model/backup' },
                            { id: 'recover', text: '恢复数据', href: 'admin.php/model/recover' },
                            { id: 'model', text: '管理备份目录', href: 'admin.php/model' },
                            { id: 'execute', text: '执行SQL语句', href: 'admin.php/model/execute' },
                        ]
                    }]
            }];
        new PageUtil.MainPage({
            modulesConfig: config
        });
    });
});
//# sourceMappingURL=main.js.map