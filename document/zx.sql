--备份开始
--创建数据库开始
CREATE SCHEMA IF NOT EXISTS `zodream` DEFAULT CHARACTER SET utf8 ;

USE `zodream` ;
--创建表开始
CREATE TABLE IF NOT EXISTS `zd_authorization` (
    `id` int(10) NOT NULL PRIMARY KEY auto_increment COMMENT '权限主键 权限表',
    `name` varchar(45) NOT NULL UNIQUE COMMENT '权限名')
ENGINE=MyISAM DEFAULT CHARSET=utf8;
--创建表结束

INSERT INTO `zd_authorization` (`id`, `name`) VALUES 
('1', 'admin'),
('2', 'edit');
--创建表开始
CREATE TABLE IF NOT EXISTS `zd_authorization_role` (
    `role_id` int(10) NOT NULL COMMENT '权限 与角色关联表',
    `authorization_id` int(10) NOT NULL)
ENGINE=MyISAM DEFAULT CHARSET=utf8;
--创建表结束

--创建表开始
CREATE TABLE IF NOT EXISTS `zd_comment` (
    `id` int(11) NOT NULL PRIMARY KEY auto_increment COMMENT '博客评论表',
    `content` text NOT NULL,
    `name` varchar(45),
    `email` varchar(100),
    `url` varchar(200),
    `ip` varchar(20),
    `create_at` int(10),
    `karma` int(11) DEFAULT 0,
    `approved` varchar(20) DEFAULT 1,
    `agent` varchar(255),
    `type` varchar(20),
    `parent` int(20) DEFAULT 0,
    `user_id` int(10) DEFAULT 0,
    `post_id` int(10))
ENGINE=MyISAM DEFAULT CHARSET=utf8;
--创建表结束

INSERT INTO `zd_comment` (`id`, `content`, `name`, `email`, `url`, `ip`, `create_at`, `karma`, `approved`, `agent`, `type`, `parent`, `user_id`, `post_id`) VALUES 
('1', '不踩踩踩', 'admin', 'admin@zodream.cn', '', '', '1460691481', '0', '1', '', '', '0', '1', '1'),
('2', '不', 'admin', 'admin@zodream.cn', '', '', '1460691667', '0', '1', '', '', '0', '1', '1'),
('3', '还行', 'admin', 'admin@zodream.cn', '', '', '1460691798', '0', '1', '', '', '0', '1', '1'),
('4', '不错啊', 'admin', 'admin@zodream.cn', '', '', '1460692344', '0', '1', '', '', '0', '1', '1');
--创建表开始
CREATE TABLE IF NOT EXISTS `zd_commentmeta` (
    `id` int(10) NOT NULL PRIMARY KEY auto_increment COMMENT '博客评论拓展表',
    `comment_id` int(10) NOT NULL,
    `name` varchar(255),
    `value` text)
ENGINE=MyISAM DEFAULT CHARSET=utf8;
--创建表结束

--创建表开始
CREATE TABLE IF NOT EXISTS `zd_forum` (
    `id` int(10) NOT NULL PRIMARY KEY auto_increment COMMENT '论坛id 论坛版块表',
    `parent` int(10) DEFAULT 0 COMMENT '上级论坛id',
    `type` enum('group','forum','sub') DEFAULT forum COMMENT ' 类型 (group:分类 forum:普通论坛 sub:子论坛)',
    `name` varchar(50) NOT NULL COMMENT '名称',
    `status` tinyint(1) DEFAULT 0 COMMENT ' 显示状态 (0:隐藏 1:正常 3:群组)',
    `position` int(10) DEFAULT 0 COMMENT '显示顺序',
    `threads` int(10) DEFAULT 0 COMMENT '主题数量',
    `todaypost` int(10) DEFAULT 0 COMMENT '今日发帖数量',
    `posts` int(10) DEFAULT 0 COMMENT ' 帖子数量')
ENGINE=MyISAM DEFAULT CHARSET=utf8;
--创建表结束

INSERT INTO `zd_forum` (`id`, `parent`, `type`, `name`, `status`, `position`, `threads`, `todaypost`, `posts`) VALUES 
('1', '0', 'group', '资源交流区', '0', '0', '0', '0', '0'),
('2', '1', 'forum', '新手报到', '0', '0', '0', '0', '0');
--创建表开始
CREATE TABLE IF NOT EXISTS `zd_friendlink` (
    `id` int(10) NOT NULL PRIMARY KEY auto_increment COMMENT '友情链接表',
    `position` int(3) DEFAULT 0 COMMENT ' 显示顺序，正序',
    `name` varchar(100) COMMENT '名称',
    `url` varchar(255) COMMENT '网址',
    `description` text COMMENT '解释说明',
    `logo` varchar(255),
    `type` int(3) DEFAULT 0)
ENGINE=MyISAM DEFAULT CHARSET=utf8;
--创建表结束

--创建表开始
CREATE TABLE IF NOT EXISTS `zd_log` (
    `id` int(10) NOT NULL PRIMARY KEY auto_increment COMMENT '日志表',
    `ip` varchar(20) NOT NULL,
    `url` varchar(255),
    `user` varchar(30) NOT NULL COMMENT '用户名',
    `event` varchar(20) NOT NULL COMMENT '事件',
    `data` text COMMENT '事件的详细情况',
    `create_at` int(10) NOT NULL COMMENT '发生时间')
ENGINE=MyISAM DEFAULT CHARSET=utf8;
--创建表结束

--创建表开始
CREATE TABLE IF NOT EXISTS `zd_login_log` (
    `id` int(10) NOT NULL PRIMARY KEY auto_increment COMMENT '登陆日志表',
    `ip` varchar(20) NOT NULL,
    `user` varchar(45) NOT NULL COMMENT '用户名',
    `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '用户登录的状态 成功或失败',
    `mode` varchar(45) COMMENT '登录的方式',
    `create_at` int(10) NOT NULL)
ENGINE=MyISAM DEFAULT CHARSET=utf8;
--创建表结束

INSERT INTO `zd_login_log` (`id`, `ip`, `user`, `status`, `mode`, `create_at`) VALUES 
('1', 'unknown', 'admin@zodream.cn', '1', '1', '1460434026'),
('2', 'unknown', 'admin@zodream.cn', '1', '1', '1460461375'),
('3', 'unknown', 'admin@zodream.cn', '1', '1', '1460461446'),
('4', 'unknown', 'admin@zodream.cn', '1', '1', '1460461520'),
('5', 'unknown', 'admin@zodream.cn', '1', '1', '1460461697'),
('6', 'unknown', 'admin@zodream.cn', '1', '1', '1460461830'),
('7', 'unknown', 'admin@zodream.cn', '1', '1', '1460461966'),
('8', 'unknown', 'admin@zodream.cn', '1', '1', '1460462005'),
('9', 'unknown', 'admin@zodream.cn', '1', '1', '1460462129'),
('10', 'unknown', 'admin@zodream.cn', '1', '1', '1460462290'),
('11', 'unknown', 'admin@zodream.cn', '1', '1', '1460462460'),
('12', 'unknown', 'admin@zodream.cn', '0', '1', '1460462553'),
('13', 'unknown', 'admin@zodream.cn', '0', '1', '1460462559'),
('14', 'unknown', 'admin@zodream.cn', '0', '1', '1460462564'),
('15', 'unknown', 'admin@zodream.cn', '1', '1', '1460468973'),
('16', 'unknown', 'admin@zodream.cn', '1', '1', '1460509352'),
('17', 'unknown', 'admin@zodream.cn', '1', '1', '1460597904'),
('18', 'unknown', 'admin@zodream.cn', '1', '1', '1460690953'),
('19', 'unknown', 'admin@zodream.cn', '1', '1', '1460712122'),
('20', 'unknown', 'admin@zodream.cn', '1', '1', '1460727859');
INSERT INTO `zd_login_log` (`id`, `ip`, `user`, `status`, `mode`, `create_at`) VALUES 
('21', 'unknown', 'admin@zodream.cn', '1', '1', '1460768421'),
('22', 'unknown', 'admin@zodream.cn', '1', '1', '1460768910'),
('23', 'unknown', 'admin@zodream.cn', '1', '1', '1460783857');
--创建表开始
CREATE TABLE IF NOT EXISTS `zd_moderator` (
    `forum_id` int(10) NOT NULL COMMENT '论坛id 版主表',
    `user_id` int(10) NOT NULL COMMENT '会员id , 版主表',
    `position` int(3) DEFAULT 0)
ENGINE=MyISAM DEFAULT CHARSET=utf8;
--创建表结束

--创建表开始
CREATE TABLE IF NOT EXISTS `zd_navigation` (
    `id` int(10) NOT NULL PRIMARY KEY auto_increment COMMENT '首页导航链接表',
    `name` varchar(100) NOT NULL,
    `url` varchar(255) NOT NULL,
    `category_id` int(10) NOT NULL,
    `position` int(10) DEFAULT 0,
    `user_id` int(10) NOT NULL)
ENGINE=MyISAM DEFAULT CHARSET=utf8;
--创建表结束

INSERT INTO `zd_navigation` (`id`, `name`, `url`, `category_id`, `position`, `user_id`) VALUES 
('1', '博客园', 'http://www.cnblogs.com/', '1', '0', '1'),
('2', '凤凰网', 'http://www.ifeng.com/', '4', '0', '1'),
('3', '百度云论坛', 'http://www.baiduyun.me/', '2', '0', '1'),
('4', 'IT之家', 'http://www.ithome.com/', '4', '0', '1'),
('5', '乌云', 'http://www.wooyun.org/', '5', '0', '1'),
('6', 'Freebuf', 'http://www.freebuf.com/', '5', '0', '1'),
('7', 'livecoding', 'https://www.livecoding.tv', '6', '0', '1');
--创建表开始
CREATE TABLE IF NOT EXISTS `zd_navigation_category` (
    `id` int(10) NOT NULL PRIMARY KEY auto_increment COMMENT '首页导航链接分类表',
    `name` varchar(20) NOT NULL UNIQUE COMMENT '导航分类的类名',
    `position` int(10) DEFAULT 0 COMMENT '排放顺序',
    `user_id` int(10) NOT NULL)
ENGINE=MyISAM DEFAULT CHARSET=utf8;
--创建表结束

INSERT INTO `zd_navigation_category` (`id`, `name`, `position`, `user_id`) VALUES 
('1', '博客', '0', '1'),
('2', '论坛', '0', '1'),
('3', '小说', '0', '1'),
('4', '新闻', '0', '1'),
('5', '安全', '0', '1'),
('6', '视频', '0', '1');
--创建表开始
CREATE TABLE IF NOT EXISTS `zd_option` (
    `name` varchar(255) NOT NULL PRIMARY KEY COMMENT '变量名 设置表',
    `value` text,
    `autoload` varchar(20) DEFAULT yes)
ENGINE=MyISAM DEFAULT CHARSET=utf8;
--创建表结束

--创建表开始
CREATE TABLE IF NOT EXISTS `zd_post` (
    `id` int(11) NOT NULL PRIMARY KEY auto_increment COMMENT '博客表',
    `title` varchar(255) NOT NULL,
    `content` text NOT NULL,
    `user_id` int(10) NOT NULL,
    `update_at` int(10),
    `create_at` int(10),
    `excerpt` text COMMENT '摘录，节选',
    `status` varchar(20) DEFAULT publish COMMENT '发布的状态',
    `comment_status` varchar(20) DEFAULT open COMMENT '评论的状态，是否可以评论',
    `ping_status` varchar(20) DEFAULT open,
    `password` varchar(20) COMMENT '密码',
    `name` varchar(200),
    `to_ping` text,
    `pinged` text,
    `parent` int(10) DEFAULT 0,
    `guid` varchar(255) COMMENT '固定链接',
    `position` int(10) DEFAULT 0,
    `type` varchar(20) DEFAULT post,
    `mime_type` varchar(20),
    `comment_count` int(10) DEFAULT 0)
ENGINE=MyISAM DEFAULT CHARSET=utf8;
--创建表结束

INSERT INTO `zd_post` (`id`, `title`, `content`, `user_id`, `update_at`, `create_at`, `excerpt`, `status`, `comment_status`, `ping_status`, `password`, `name`, `to_ping`, `pinged`, `parent`, `guid`, `position`, `type`, `mime_type`, `comment_count`) VALUES 
('1', '不错吧', '&lt;p&gt;这真是极好的&lt;/p&gt;&lt;pre class=&quot;brush:php;toolbar:false&quot;&gt;function&amp;nbsp;termAction()&amp;nbsp;{
&amp;nbsp;&amp;nbsp;&amp;nbsp;$data&amp;nbsp;=&amp;nbsp;EmpireModel::query(&amp;#39;term&amp;#39;)-&amp;gt;find();
&amp;nbsp;&amp;nbsp;&amp;nbsp;$this-&amp;gt;show(array(
&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;#39;data&amp;#39;&amp;nbsp;=&amp;gt;&amp;nbsp;$data
&amp;nbsp;&amp;nbsp;&amp;nbsp;));
}&lt;/pre&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '1', '', '', '', 'publish', 'open', 'open', '', '', '', '', '0', '', '0', 'post', '', '4'),
('2', '不错吧', '&lt;p&gt;对啊&lt;/p&gt;&lt;pre class=&quot;brush:php;toolbar:false&quot;&gt;function&amp;nbsp;termAction()&amp;nbsp;{
&amp;nbsp;&amp;nbsp;&amp;nbsp;$data&amp;nbsp;=&amp;nbsp;EmpireModel::query(&amp;#39;term&amp;#39;)-&amp;gt;find();
&amp;nbsp;&amp;nbsp;&amp;nbsp;$this-&amp;gt;show(array(
&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;#39;data&amp;#39;&amp;nbsp;=&amp;gt;&amp;nbsp;$data
&amp;nbsp;&amp;nbsp;&amp;nbsp;));
}&lt;/pre&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '1', '', '', '', 'publish', 'open', 'open', '', '', '', '', '0', '', '0', 'post', '', '0');
--创建表开始
CREATE TABLE IF NOT EXISTS `zd_postmeta` (
    `id` int(11) NOT NULL PRIMARY KEY auto_increment COMMENT '博客拓展表',
    `post_id` int(10) NOT NULL,
    `name` varchar(255) NOT NULL,
    `value` text)
ENGINE=MyISAM DEFAULT CHARSET=utf8;
--创建表结束

--创建表开始
CREATE TABLE IF NOT EXISTS `zd_role` (
    `id` int(10) NOT NULL PRIMARY KEY auto_increment COMMENT '角色的主键 角色表',
    `name` varchar(45) NOT NULL UNIQUE COMMENT '角色名')
ENGINE=MyISAM DEFAULT CHARSET=utf8;
--创建表结束

INSERT INTO `zd_role` (`id`, `name`) VALUES 
('1', '管理员');
--创建表开始
CREATE TABLE IF NOT EXISTS `zd_role_user` (
    `user_id` int(10) NOT NULL COMMENT '用户id 用户与角色关联表',
    `role_id` int(10) NOT NULL COMMENT '角色id')
ENGINE=MyISAM DEFAULT CHARSET=utf8;
--创建表结束

--创建表开始
CREATE TABLE IF NOT EXISTS `zd_talk` (
    `id` int(10) NOT NULL PRIMARY KEY auto_increment COMMENT '网站发展历程表',
    `content` varchar(255) NOT NULL COMMENT '个人说说的内容',
    `user_id` int(10) NOT NULL,
    `create_at` int(10) NOT NULL)
ENGINE=MyISAM DEFAULT CHARSET=utf8;
--创建表结束

INSERT INTO `zd_talk` (`id`, `content`, `user_id`, `create_at`) VALUES 
('1', 'Zodream 正式改版！', '1', '1460436409');
--创建表开始
CREATE TABLE IF NOT EXISTS `zd_term` (
    `id` int(10) NOT NULL PRIMARY KEY auto_increment COMMENT '博客分类表',
    `name` varchar(200) NOT NULL,
    `slug` varchar(200),
    `group` int(10) DEFAULT 0)
ENGINE=MyISAM DEFAULT CHARSET=utf8;
--创建表结束

INSERT INTO `zd_term` (`id`, `name`, `slug`, `group`) VALUES 
('1', '学习', 'xx', '0'),
('2', '源码', 'ym', '0');
--创建表开始
CREATE TABLE IF NOT EXISTS `zd_term_post` (
    `post_id` int(10) NOT NULL PRIMARY KEY COMMENT '博客与分类关联表',
    `term_id` int(10) NOT NULL,
    `position` int(10) DEFAULT 0)
ENGINE=MyISAM DEFAULT CHARSET=utf8;
--创建表结束

--创建表开始
CREATE TABLE IF NOT EXISTS `zd_term_taxonomy` (
    `id` int(10) NOT NULL PRIMARY KEY auto_increment COMMENT '分类系统表',
    `term_id` int(10) NOT NULL,
    `taxonomy` varchar(32) NOT NULL,
    `description` text,
    `parent` int(10) DEFAULT 0,
    `count` int(10) DEFAULT 0)
ENGINE=MyISAM DEFAULT CHARSET=utf8;
--创建表结束

--创建表开始
CREATE TABLE IF NOT EXISTS `zd_termmeta` (
    `id` int(10) NOT NULL PRIMARY KEY auto_increment COMMENT '博客分类拓展表',
    `term_id` int(10) NOT NULL,
    `name` varchar(255) NOT NULL,
    `value` text)
ENGINE=MyISAM DEFAULT CHARSET=utf8;
--创建表结束

--创建表开始
CREATE TABLE IF NOT EXISTS `zd_thread` (
    `id` int(10) NOT NULL PRIMARY KEY auto_increment COMMENT '主题id 论坛主题表',
    `forum_id` int(10) NOT NULL COMMENT '上级论坛',
    `title` varchar(100) NOT NULL,
    `readperm` int(3) DEFAULT 0 COMMENT ' 阅读权限',
    `user_id` int(10) NOT NULL,
    `user_name` varchar(45) NOT NULL,
    `replies` int(10) DEFAULT 0 COMMENT '回复次数',
    `views` int(10) DEFAULT 0 COMMENT '浏览次数',
    `update_at` int(10) COMMENT '最后发表时间',
    `update_user` int(10) COMMENT ' 最后发表人id',
    `create_at` int(10))
ENGINE=MyISAM DEFAULT CHARSET=utf8;
--创建表结束

INSERT INTO `zd_thread` (`id`, `forum_id`, `title`, `readperm`, `user_id`, `user_name`, `replies`, `views`, `update_at`, `update_user`, `create_at`) VALUES 
('1', '2', '不错啊', '0', '1', 'admin', '1', '16', '1460714686', '1', '1460712150');
--创建表开始
CREATE TABLE IF NOT EXISTS `zd_thread_post` (
    `id` int(10) NOT NULL PRIMARY KEY auto_increment COMMENT ' 帖子id 论坛帖子表',
    `forum_id` int(10) NOT NULL COMMENT ' 论坛id',
    `thread_id` int(10) NOT NULL COMMENT '主题id',
    `content` text NOT NULL COMMENT '内容',
    `first` tinyint(1) DEFAULT 0 COMMENT '是否是首贴 及主题的一楼内容',
    `user_id` int(10) NOT NULL,
    `user_name` varchar(30) NOT NULL,
    `ip` varchar(20),
    `create_at` int(10))
ENGINE=MyISAM DEFAULT CHARSET=utf8;
--创建表结束

INSERT INTO `zd_thread_post` (`id`, `forum_id`, `thread_id`, `content`, `first`, `user_id`, `user_name`, `ip`, `create_at`) VALUES 
('1', '2', '1', '不错啊', '1', '1', 'admin', '', '1460712150'),
('2', '2', '1', '不得', '0', '1', 'admin', 'unknown', '1460713224'),
('3', '2', '1', '正的吗u', '0', '1', 'admin', 'unknown', '1460713237'),
('4', '2', '1', '不错的
', '0', '1', 'admin', 'unknown', '1460714067'),
('5', '2', '1', '不错的', '0', '1', 'admin', 'unknown', '1460714428'),
('6', '2', '1', '不错', '0', '1', 'admin', 'unknown', '1460714490'),
('7', '2', '1', '不错啊', '0', '1', 'admin', 'unknown', '1460714686');
--创建表开始
CREATE TABLE IF NOT EXISTS `zd_tree` (
    `id` int(10) NOT NULL PRIMARY KEY auto_increment COMMENT '无限树菜单表',
    `name` varchar(45) NOT NULL COMMENT '标题名',
    `url` varchar(45) COMMENT '所代表的网址',
    `left` int(10) COMMENT '左值',
    `right` int(10) COMMENT '右值',
    `parent_id` int(10) DEFAULT 0,
    `level` int(10) DEFAULT 0 COMMENT '水平深度',
    `position` int(10) DEFAULT 0 COMMENT '位置，顺序')
ENGINE=MyISAM DEFAULT CHARSET=utf8;
--创建表结束

--创建表开始
CREATE TABLE IF NOT EXISTS `zd_user` (
    `id` int(10) NOT NULL PRIMARY KEY auto_increment COMMENT '用户表主键Id 用户表',
    `name` varchar(30) NOT NULL UNIQUE COMMENT '用户名',
    `email` varchar(100) NOT NULL UNIQUE COMMENT '邮箱',
    `password` varchar(64) NOT NULL COMMENT '密码',
    `token` varchar(60) COMMENT '自动登陆的认证码',
    `login_num` int(10) DEFAULT 0 COMMENT '登录次数',
    `update_ip` varchar(20) COMMENT '最近登录IP',
    `update_at` int(10) COMMENT '最近登录时间',
    `previous_ip` varchar(20) COMMENT '上一次登录ip',
    `previous_at` int(10) COMMENT '上一次登录时间',
    `create_ip` varchar(20),
    `create_at` int(10) COMMENT '注册时间')
ENGINE=MyISAM DEFAULT CHARSET=utf8;
--创建表结束

INSERT INTO `zd_user` (`id`, `name`, `email`, `password`, `token`, `login_num`, `update_ip`, `update_at`, `previous_ip`, `previous_at`, `create_ip`, `create_at`) VALUES 
('1', 'admin', 'admin@zodream.cn', '$2y$10$YVQnYGuVkNPTciRFgCL6U.Li4kAjPlbvBEZS5YgE3dcsqU33NDKk6', '', '20', 'unknown', '1460783857', 'unknown', '1460768910', 'unknown', '1460434011');
--创建表开始
CREATE TABLE IF NOT EXISTS `zd_visit` (
    `ip` int(10) NOT NULL DEFAULT 0 COMMENT '用户IP 浏览记录表',
    `view` int(10) NOT NULL DEFAULT 0 COMMENT '访问次数')
ENGINE=MyISAM DEFAULT CHARSET=utf8;
--创建表结束

--创建数据库结束


--备份结束