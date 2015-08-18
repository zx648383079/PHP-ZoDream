<?php
	/****************************************************
	*控制器基类
	*
	*
	*******************************************************/
	namespace App\Controller;
	
	

	class Controller{
		
		private $smarty;
		
		function __construct()
		{
			
			$config=config('smarty');
			
			$this->smarty=new \Smarty();
			
			$this->smarty->debugging =defined(DEBUG)?DEBUG:FALSE;
			//$this->smarty->caching = true;
			$this->smarty->cache_lifetime = 120;
			$this->smarty->cache_dir=$config['dir'].'cache/';         //缓存目录
			$this->smarty->compile_dir=$config['dir'].'compile/';     //编译目录
			$this->smarty->config_dir=$config['dir'].'config/';       //模板配置文件信息
			// 设置模板目录
			$this->smarty->template_dir=$config['dir'];
			
			//修改左右边界符号
			$this->smarty -> left_delimiter=$config['left'];
			$this->smarty -> right_delimiter=$config['right'];
			
			$this->smarty->assign('title','首页');
			$this->smarty->assign('lang',getLang());
			
			$this->smarty->registerPlugin('function','jcs','jcs');
			
		}
		
		
		
		//要传的数据
		function send($key,$value=null,$cache=FALSE)
		{
			$this->smarty->assign($key,$value,$cache);
		}
		
		//加载视图
		function show($name="index")
		{
			$this->smarty->display($name.'.html');
		} 
		
		//返回JSON数据
		function ajaxJson($data,$type='JSON')
		{
			switch (strtoupper($type)){
	            case 'JSON' :
	                // 返回JSON数据格式到客户端 包含状态信息
	                header('Content-Type:application/json; charset=utf-8');
	                exit(json_encode($data));
	            case 'XML'  :
	                // 返回xml格式数据
	                header('Content-Type:text/xml; charset=utf-8');
	                exit(xml_encode($data));
	            case 'JSONP':
	                // 返回JSON数据格式到客户端 包含状态信息
	                header('Content-Type:application/json; charset=utf-8');
	                $handler  =   isset($_GET['callback']) ? $_GET['callback'] : 'jsonpReturn';
	                exit($handler.'('.json_encode($data).');');  
	            case 'EVAL' :
	                // 返回可执行的js脚本
	                header('Content-Type:text/html; charset=utf-8');
	                exit($data);            
	        }
			
			exit;
		}
	}