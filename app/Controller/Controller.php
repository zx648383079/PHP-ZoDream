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
		
		private $data;
		
		function __construct()
		{
			
			$this->data = Main::config('app');
			$this->data['lang'] = Lang::$language;
			
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
		function send($key,$value="")
		{
			if(empty($value))
			{
				$this->data['data']=$key;
			}else
			{
				$this->data[$key]=$value;
			}
		}
		
		
		
		//加载视图
		function show($name = "index")
		{
			if ( APP_API )
			{
				$this->ajaxJson( $this->data );
			}else{
				
				if(!empty($this->data))
				{
					extract($this->data);    //从数组导成变量；
				}
				
				
				
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