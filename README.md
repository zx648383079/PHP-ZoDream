# PHP-ZoDream

[Zodream](https://github.com/zodream/zodream) is a web application framework. There contains some examples of [zodream](https://github.com/zodream/zodream).

👉[中文](README.zh.md)

# Development Notes

The current system does not have speed or safety as its primary purpose. Please do not use this system in a production environment. If you have related bugs or optimization suggestions, please submit an issue.

In the next step, instead of focusing on the development of new modules, the existing modules will be transformed and optimized.

Increase the linkage within each module.


### Ignore PHP version check
```shell
composer install --ignore-platform-reqs
```

### Resource file compilation instructions

```cmd

npm i

gulp //build home directory  UserInterface/assets

gulp Blog  //build blog  Module/Blog/UserInterface/assets

gulp --prod  //build home directory and do code compression   UserInterface/assets

gulp CMS-default //build default theme resources under CMS  Module/CMS/UserInterface/default/assets

```


# Module list

|                                          Module Name                                          | Introduce                                                                                         | Status                                                           | DEMO                              |
|:---------------------------------------------------------------------------------------------:| :------------------------------------------------------------------------------------------- | :------------------------------------------------------------: | :-------------------------------- |
|          [Blog](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Blog)           | common blog system                                                                               | Completed 1.0                                                      | [demo](https://zodream.cn/blog)    |
|    [API Document](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Document)     | API documentation system, supporting code generation                                                                                 | Completed 1.0                                                      | [demo](https://zodream.cn/doc)                            |
|       [Finance](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Finance)        | personal financial system                                                                                 | Completed 1.0                                                      | [demo](https://zodream.cn/finance) |
|          [Novel](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Book)          | Novel system, include web and h5, automatic crawler function to be developed                                                 | Completed 1.0                                                      | [demo](https://zodream.cn/book)                            |
|     [WeChat Manage](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/WeChat)     | Official account management system, supports multiple official account management, supports different scenarios (similar to account binding, sign-in, word games, etc.), supports simulated WeChat automatic reply                                                           | in development                                                    | not online                            |
|       [Online Disk](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Disk)       | Online Disk | in development, Uploading, downloading, playing video, music, sharing, APP download and installation have been completed | not online                            |
|                         [OAUTH 2.0](https://github.com/zodream/oauth)                         | Based on OAUTH 2.0 server, the docking function has been completed                                                       | in development                                                     | not online                            |
|                       [Generate Helper](https://github.com/zodream/gzo)                       | Visual and convenient operation of open modules, suitable for code generation   | Completed 1.0  | local use |
|          [Auth](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Auth)           | For the time being, only third-party login, local login, and registration functions are completed, and permission control is to be developed | Completed 1.0                                                      | [demo](https://zodream.cn/auth)    |
|           [CMS](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/CMS)            | Content management system, support template theme import, complete URL navigation, game list, video theme etc | Completed 1.0                                                         | [demo](https://zodream.cn/cms)                            |
|           [CAS](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Cas)            | cas-based single sign-on  | Completed 1.0                                          | not online                            |
|           [RPC](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/RPC)            | json rpc                                                                            | in development | not online                            |
|      [Online Chat ](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Chat)       | Online Chat| TODO | not online                            |
|          [BBS](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Forum)           | BBS Forum| Completed 1.0 | [demo](https://zodream.cn/forum) |
|      [Schedule](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Schedule)       | System scheduled task system, automatically run background tasks at specified time | Completed 1.0                                                         | not online                            |
|         [My plan](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Task)         | Personal plan task system, add Pomodoro time management, and time record statistics  | Completed 1.0 | [demo](https://zodream.cn/task)                           |
|          [Exam](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Exam)           | Subject-based question bank, used for brushing questions, advanced tree of skills to be developed, and exclusive certification of skills, the ultimate goal: to achieve clear grading of any skills, guide users to advance, verify users' own skills deficiencies, and support test papers               | Completed 1.0                                                         | not online                            |
|          [Shop](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Shop)           | Mall system, including mobile PC terminal, plan to angularize and develop APP (Flutter, UWP), applet, Vue mobile version | In development   | not online                           |
|      [LOG Viewer](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/LogView)      | The LOG viewing system mainly realizes the reading and display of iis logs, which can be marked, inferred and sorted according to the mark, and searched for suspicious access. | Completed 1.0                                                         | not online                            |
|     [Micro Blog](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/MicroBlog)     | Support basic posting of text, pictures, and videos on Weibo, support forwarding, and support comments  | Completed 1.0       | not online                           |                     |
|       [Legwork](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Legwork)        | Support custom forms to place orders, support independent orders | Completed 1.0  | not online                           |
|      [Short video](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Video)       | Support users to upload videos and select background music | Completed 1.0  | not online                            |
|       [Short url](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Short)        |Support external links (directly redirect to links), support internal links (direct program processing, the URL display is still a short link, but the actual execution address and parameters are official links)| Completed 1.0 | not online                            |
|           [SEO](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/SEO)            |  For basic functions, including system settings, cache processing, sitemaps and other basic functions | Completed 1.0  | online                            |
| [Resource Store](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/ResourceStore) |  Support template package upload | Completed 1.0 | [demo](https://zodream.cn/demo)                        |
|       [Feedback](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/Contact)       |  Including link management, feedback management, subscription management  | Completed 1.0 | [demo](https://zodream.cn/)                        |
| [Online service](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/OnlineService) |  Only provide api, interface and management background see [Angular-ZoDream](https://github.com/zx648383079/Angular-ZoDream) | Completed 1.0   | not online                        |
|  [Open Platform](https://github.com/zx648383079/PHP-ZoDream/tree/master/Module/OpenPlatform)  | Application management of application (including client and interface) appid | Completed 1.0 | [demo](https://zodream.cn/)                        |


# Live

URL：https://zodream.cn

## Version

### Backend

[PHP-ZoDream](https://github.com/zx648383079/PHP-ZoDream)：Full version, using PHP programming language, self-made framework

[godream](https://github.com/zx648383079/godream)：Blog and chat room modules, using the Go programming language, based on the gin framework

[netdream](https://github.com/zx648383079/netdream)：Blog module, using C# programming language, based on Net Core framework

### Frontend

[Angular-ZoDream](https://github.com/zx648383079/Angular-ZoDream)：Most modules, including the admin backend, use the typescript language and are based on the angular 13 framework

### App

[Flutter-Shop](https://github.com/zx648383079/Flutter-Shop): Mall module, using dart language, based on flutter framework

[Mini-Shop](https://github.com/zx648383079/Mini-Shop)：Mall module, using typescript language, WeChat Mini Program depends on gulp-vue2mini code conversion

[Vue-Shop](https://github.com/zx648383079/Mini-Shop)：Mall module, using typescript language, based on vu3 framework

There are other project, but none are perfect.


## Thanks

[![JetBrains](html/assets/images/jetbrains.svg)](https://www.jetbrains.com/?from=PHP-ZoDream)


Updated: 2022/04/29

