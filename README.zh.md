# PHP-ZoDream

[Zodream](https://github.com/zodream/zodream) 是一个使用PHP的Web框架。这个项目是基于 [zodream](https://github.com/zodream/zodream) 的一些示例。

# 开发说明

当前系统不以速度或安全为主要目的。请不要将本系统用于生产环境。如有相关BUG或优化建议，请提交issue。

下一步，不再注重新模块的开发，将改造优化现有模块。

增加各模块内部的联动。

```text
所有的页面和管理端有些滞后，没有实时更新，更多的是作为接口使用，主要页面和新功能还是看 Angular-ZoDream 这个项目
```

### 忽略PHP版本检查
```shell
composer install --ignore-platform-reqs
```

### 生产环境部署

```shell
composer install --no-dev
```

### 资源文件编译说明

```cmd

npm i

gulp // 编译主目录  UserInterface/assets

gulp Blog  // 编译博客  Module/Blog/UserInterface/assets

gulp --prod  // 编译主目录并进行代码压缩   UserInterface/assets

gulp CMS-default // 编译CMS下的default主题资源  Module/CMS/UserInterface/default/assets

```

# 目录


# 模块列表

|                                          模块名                                          | 介绍                                                                                         |                              状态                               | DEMO                              |
|:-------------------------------------------------------------------------------------:| :------------------------------------------------------------------------------------------- |:-------------------------------------------------------------:| :-------------------------------- |
|      [博客系统](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Blog)       | 普通的博客系统                                                                               |                            已完成1.0                             | [demo](https://zodream.cn/blog)    |
|   [API文档系统](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Document)   | API文档系统，支持代码生成                                                                                 |                            已完成1.0                             | [demo](https://zodream.cn/doc)                            |
|    [个人财务系统](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Finance)    | 个人财务系统                                                                                 |                            已完成1.0                             | [demo](https://zodream.cn/finance) |
|      [小说系统](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Book)       | 小说系统，已完成pc手机端，自动爬虫功能待开发                                                 |                            已完成1.0                             | [demo](https://zodream.cn/book)                            |
|    [公众号管理系统](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/WeChat)    | 公众号管理系统，支持多公众号管理，支持不同场景（类似账号绑定，签到，文字游戏等场景） ，支持模拟微信自动回复                                                           |                             后台开发中                             | 未上线                            |
|      [网盘系统](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Disk)       | 网盘系统                                                                                     |                开发中，已完成上传、下载，播放视频、音乐、分享、APP下载安装                | 未上线                            |
|   [OAUTH 2.0 系统](https://github.com/zodream/oauth)                    | 基于OAUTH 2.0 的服务端，对接功能已完成                                                       |                             后台开发中                             | 未上线                            |
|  [模板生成系统](https://github.com/zodream/gzo)                        | 可视化便捷操作开放模块，适用于代码生成                                                       |                            已完成1.0                             | 本地使用                          |
|     [用户管理系统](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Auth)      | 暂时只完成第三方登录、本地登录、注册功能，权限控制待开发                                     |                            已完成1.0                             | [demo](https://zodream.cn/auth)    |
|      [CMS系统](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/CMS)       | 内容管理系统，支持模板主题导入，已完成网址导航、游戏榜单、视频主题等                                                                                |                            已完成1.0                             | [demo](https://zodream.cn/cms)                            |
|      [CAS系统](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Cas)       | 基于cas的单点登录                                                                            |                            已完成1.0                             | 未上线                            |
|      [RPC模块](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/RPC)       | json rpc                                                                            |                              开发中                              | 未上线                            |
|      [聊天系统](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Chat)       | 聊天室                                                                                       |                              待开发                              | 未上线                            |
|    [BBS论坛系统](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Forum)     | BBS论坛系统                                                                                  |                            已完成1.0                             | [demo](https://zodream.cn/forum)                            |
|    [任务系统](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Schedule)     | 系统计划任务系统，指定时间自动运行后台任务                                                                                |                            已完成1.0                             | 未上线                            |
|     [个人计划系统](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Task)      | 个人计划任务系统，新增番茄时间管理，及时间记录统计                                                                                 |                            已完成1.0                             | [demo](https://zodream.cn/task)                           |
|      [题库系统](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Exam)       | 分科目题库，刷题用，待开发技能进阶树、技能专属认证，最终目标：实现对任何技能明确分阶，指导用户进阶，验证用户自身技能缺陷，支持试卷               |                            已完成1.0                             | 未上线                            |
|      [商城系统](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Shop)       | 商城系统，包括手机PC端，计划angular化及开发APP（Flutter、UWP）、小程序、Vue手机版                                                                                   |                              开发中                              | 未上线                            |
|   [LOG查看系统](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/LogView)    | LOG查看系统，主要实现对iis日志的读取显示，可以标记，并根据标记进行推断整理，进行可疑访问查找 |                            已完成1.0                             | 未上线                            |
|   [可视化编辑系统](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Template)   | 可视化拖拽编辑系统系统，可视化编辑功能适配pc、手机端，在手机上也能制作好看的网页了！！                                                                       |                              已完成1.0                               | 未上线                            |
|    [微博系统](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/MicroBlog)    | 支持发微博基本的发布文字、图片、视频，支持转发，支持评论                                                                       |                              开发中                              | 未上线                            |
|     [族谱系统](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Family)      | 支持添加人员，待开发功能：前台：关系树、生成族谱pdf及打印，根据账号绑定可查询其他人的称呼（有可能引入人脸识别，ar智能识别显示称呼）  后台：支持多任配偶，弹窗选择关系任务，支持赘婿，支持时间时辰转化                                                                       |                              开发中                              | 未上线                            |
|    [跑腿服务系统](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Legwork)    | 支持自定义表单下单，支持自主接单                                                                       |                            已完成1.0                             | 未上线                            |
|     [短视频系统](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Video)      | 支持用户上传视频及选择背景音乐，目前只有小程序，暂不支持基于用户标签推荐                                                                       |                            已完成1.0                             | 未上线                            |
|     [短链接系统](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Short)      | 支持外部链接（直接重定向到链接），支持内部链接（直接进行程序处理，网址显示依旧是短链接，但实际执行的地址和参数为正式链接）                                                                       |                            已完成1.0                             | 未上线                            |
|      [SEO系统](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/SEO)       |   为基本功能，包括系统设置、缓存处理、站点地图等基本功能                                                                     |                            已完成1.0                             | 已上线                            |
|  [资源系统](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/ResourceStore)  |  支持模板打包上传                                                                     |                            已完成1.0                             | [demo](https://zodream.cn/demo)                        |
|     [反馈系统](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Contact)     |  包括友情链接管理、反馈管理、订阅管理                                                                     |                            已完成1.0                             | [demo](https://zodream.cn/)                        |
| [在线客服系统](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/OnlineService) |  只提供api，界面及管理后台见[Angular-ZoDream](https://github.com/zx648383079/Angular-ZoDream)                                                                     |                            已完成1.0                             | 未上线                        |
| [开发平台系统](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/OpenPlatform)  |  应用（包括客户端、接口）appid的申请管理                                                                     |                            已完成1.0                             | [demo](https://zodream.cn/)                        |


# 进度

官网：https://zodream.cn

## 前后端分离版本

### 后端：

[PHP-ZoDream](https://github.com/zx648383079/PHP-ZoDream)：完整版，使用 PHP 编程语言，自制框架

[godream](https://github.com/zx648383079/godream)：博客及聊天室模块，使用 Go 编程语言，基于 gin 框架

[netdream](https://github.com/zx648383079/netdream)：博客模块，使用 C# 编程语言，基于 Net Core 框架

### 前端：

[Angular-ZoDream](https://github.com/zx648383079/Angular-ZoDream)：大部分模块，包括管理后台，使用 typescript 语言，基于 angular 13 框架

### 客户端

[Flutter-Shop](https://github.com/zx648383079/Flutter-Shop): 商城模块，使用 dart 语言，基于 flutter 框架

[Mini-Shop](https://github.com/zx648383079/Mini-Shop)：商城模块，使用 typescript 语言，微信小程序 依赖 gulp-vue2mini 代码转换

[Vue-Shop](https://github.com/zx648383079/Mini-Shop)：商城模块，使用 typescript 语言，基于 vu3 框架

还有其他客户端，但是都不完善。


## 感谢

[![JetBrains](html/assets/images/jetbrains.svg)](https://www.jetbrains.com/?from=PHP-ZoDream)


更新时间：2023/02/22

