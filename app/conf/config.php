<?php
	/****************************************************
	*配置文件
	*
	*
	*******************************************************/
	
	return array(
		'app' => array(                           //网站信息
			'title' => '个人财务系统'
		),
		'mysql' => array(							//MYSQL数据库的信息
			'host' => 'localhost',                //服务器
            'port' => '3306',						//端口
			'database' => 'test',				//数据库
			'user' => 'root',						//账号
			'password' => 'root',					//密码
			'prefix' => '',					//前缀
			'encoding' => 'utf8'					//编码
		),
		'upload' => array(
			'maxsize' => '',                  //最大上传大小 ，单位kb
			'allowtype' => '',				//允许上次类型，用‘；’分开
			'savepath' => ''               //文件保存路径
		),
        'wechat' => array(					//微信
			'token' => 'zxzszh'
		),
		'smarty' => array(
			'dir' => 'templates/',
			'left' => '({',
			'right' => '})'
		)
	);