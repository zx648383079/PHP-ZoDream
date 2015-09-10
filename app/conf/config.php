<?php
	/****************************************************
	*配置文件
	*
	*
	*******************************************************/
	
	return array(
		'app' => array(                           //网站信息
			'title' => '主页',
			'host' => 'http://c.test:8080/',                          //主目录
			'url' => 0
		),
		'view' =>array(                           //视图文件信息
			'dir' => 'app/view',
			'ext' => '.php'
		),
		'mysql' => array(							//MYSQL数据库的信息
			'host' => 'localhost',                //服务器
            'port' => '3306',						//端口
			'database' => 'wechat',				//数据库
			'user' => 'root',						//账号
			'password' => 'root',					//密码
			'prefix' => 'zx_',					//前缀
			'encoding' => 'utf8'					//编码
		),
		'upload' => array(
			'maxsize' => '',                  //最大上传大小 ，单位kb
			'allowtype' => '',				//允许上次类型，用‘；’分开
			'savepath' => ''               //文件保存路径
		),
        'wechat' => array(					//微信
			'token' => 'zxzszh',
			'access_token' => 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=APPID&secret=APPSECRET'
		),
		'verify' => array(                    //验证码的配置信息
			'length' => 4,
			'width' => 150,
			'height' => 50,
			'font' => 'asset/font/AcademyKiller.ttf'
		),
		'short' => array(                        //手动添加短链接
			'm' => 'message.index',
			'a' =>'admin.index',
			'*' => 'home.index'                  //默认
		)
	);