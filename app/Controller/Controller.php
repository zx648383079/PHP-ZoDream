<?php
	/****************************************************
	*控制器基类
	*
	*
	*******************************************************/
	namespace App\Controller;
	
	use App\Main;
	use App\Lib\Lang;
	use App\Lib\Validation;

	class Controller{
		function __construct()
		{
			Main::$data = Main::config('app');
			Main::$data['lang'] = Lang::$language;
		}
		
		//验证数据
		function validata($request,$param,$return=FALSE)
		{
			$_vali = new Validation();
			$result = $_vali->make($request,$param);
			if( $isRer )
			{
				return $_vali->error;
			}else{
				
			}
		}
		
		//要传的数据
		function send($key , $value = "")
		{
			if(empty($value))
			{
				Main::$data['data'] = $key;
			}else
			{
				Main::$data[$key] = $value;
			}
		}
		
		
		
		//加载视图
		function show($name = "index")
		{
			if ( APP_API )
			{
				$this->ajaxJson(Main::$data);
			}else{
				header( 'Content-Type:text/html;charset=utf-8 ');
				Main::extend($name);
				exit;
			}
			
		} 
		
		//返回JSON数据
		function ajaxJson($data,$type = 'JSON')
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
		
		function showImg($img)
		{
			header('Content-type:image/png');
			imagepng($img);
			imagedestroy($img);
		}
	}