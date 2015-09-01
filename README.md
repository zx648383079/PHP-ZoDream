# PHP-WeChat
PHP微信公众平台开发

# 说明
SAE认证通过了，开始编写

1.进行MVC分离，主要参考ThinkPHP框架，但全部是原生编程，不会借助任何现成框架，目前还只是个花架子，慢慢完善吧

# 文件目录

PHP-WeChat

>app    		php总文件目录

>>Lib			类文件

>>conf			配置文件

>>Controller	控制器

>>Model			针对具体表的连接

>>view			视图文件

>asset			资源文件

>>css			css样式

>>font			字体文件

>>img			图片资源

>>js			js脚本

>>lang			语言包

>typings		VSCode自动提示文件

>vendor			Composer生成的主文件

>>composer		Composer生成的自动加载文件

>>smarty		Smarty

# 修改
1.增加Angular.js的支持

2.引入PDO连接数据库

3.优化登录

4.引入url 重写

5.优化 installl.php

6.增加验证码

7.增加生成二维码

8.修改传值失败问题

9.增加网址的识别            识别 `/a/v` `c.*/v` `?c= &v= `

10.增加代码监控错误输出

11.增加多语言支持

12.优化对SQL语句的过滤

13.增加自动加载类文件

14.增加ip监控

15.引入Composer实现自动加载

`16.引入Smarty实现分离`

17.引入NAMESPACE,初步实现NAMESPACE管理

18.添加短链接功能


已知问题
-------

没有很好的控制前端图片地址


# 更多详情请关注微信 `zx_zszh`

更新时间：2015/8/18

