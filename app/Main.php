<?php
namespace App;	
	/*****************************************************
	*全局方法
	*
	*
	*
	********************************************************/
use App\Lib\Lang;

class Main{
	
	/**
	* 获取配置文件
	*
	* @access globe
	*
	* @param string|null $key 要获取的配置名
	* @return array,
	*/
	public static function config( $key = null )
	{
		$configs=require(APP_DIR."/app/conf/config.php");
		if(!empty($key))
		{
			$arr = explode('.',$key);
			foreach ($arr as $value) {
				if(isset($configs[$value]))
				{
					$configs = $configs[$value];
				}else{
					$configs = '';	
					continue;
				}
			}
		}
		return $configs;
	}
	
	/**
	* 产生完整的网址
	*
	* @access globe
	*
	*/
	public static function url($file,$echo = TRUE)
	{
		$url = APP_URL.$file;
		if($echo)
		{
			echo $url;
		}else{
			return $url;	
		} 
	}	

	/**
	* Smarty中加载的自定义方法 主要是加载 js、css 文件
	*
	* @access globe
	*
	* @param array $file 获取到的值
	*
	* @return string     返回的值即输出的值
	*/
	public static function jcs($file)
	{
		$result='';
		$arr=explode('.',$file);
		if(count($arr)==1)
		{
			$result= '<script src="'.self::url('asset/js/'.$file.'.js',false).'"></script>';
		}else{
			switch(end($arr))
			{
				case 'js':
					$result= '<script src="'.self::url('asset/js/'.$file,false).'"></script>';
					break;
				case 'css':
					$result= '<link rel="stylesheet" type="text/css" href="'.self::url('asset/css/'.$file,false).'"/>';
					break;
				default:
					$result= '<script src="'.self::url('asset/js/'.$file,false).'.js"></script>';
					break;
			}
		}
		echo $result;
	}

	/**
	* 跳转页面
	*
	* @access globe
	*
	* @param string $url 要跳转的网址
	* @param int $time 停顿的时间
	* @param string $msg 显示的消息.
	*/
	public static function redirect($url, $time=0, $msg='') {
		//多行URL地址支持
		$url        = str_replace(array("\n", "\r"), '', $url);
		if (empty($msg))
			$msg    = "系统将在{$time}秒之后自动跳转到{$url}！";
		if (!headers_sent()) {
			// go
			if (0 === $time) {
				header('Location: ' . $url);
			} else {
				header("refresh:{$time};url={$url}");
				echo($msg);
			}
			exit();
		} else {
			$str    = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
			if ($time != 0)
				$str .= $msg;
			exit($str);
		}
		
	}
	
	public static $data;
	/**
	* 包含文件
	*
	* @access globe
	*
	* @param string $fle 路径加文件名
	* @param string $ext 拓展名
	* @,
	*/
	public static function extend( $name , $ext = '.php')
	{
		$configs = self::config('view');
				
		$view_dir = isset($configs['dir'])?$configs['dir']:'view';
		$view_ext = isset($configs['ext'])?$configs['ext']:$ext;
		
		$name = str_replace('.','/',$name);
		
		$file = APP_DIR.'/app/'.$view_dir.'/'.$name;
		
		$file = str_replace('//','/',$file);
		
		if(substr( $ext , 0, 1 ) != '.')
		{
			$ext = '.'.$ext;
		}
		
		extract(self::$data);
		
		include($file.$ext);
	}
	
	/**
	* 判断是否是首页
	*
	* @access globe
	*
	* @return true|false,
	*/
	public static function is_home()
	{
		$home = false;
		if( !isset($_GET['c']) || $_GET['c'] == "home" )
		{
			$home = true;
		}
	}

	/**
	* 执行短链接
	*
	* @access globe
	*
	* @return true|false,
	*/
	public static function short()
	{
		$shorts = self::config('short');
		
		$key = isset($_GET['s'])?$_GET['s']:'*';
		
		if(isset($shorts[$key]))
		{
			$arr = explode('.',$shorts[$key]);
			self::loadController($arr[0],$arr[1]);
		}
		
	}
	/**
	* 解析控制器和视图
	*
	* @access globe
	*
	*/
	public static function getCAV()
	{
		$url = self::request_uri();
		
	
		//  /c/v 取最后两个        /c.*/v       ?c= & v=
		
		$arr = explode('?',$url);
		
		$arr1=explode('/',$arr[0]);
		
		$v=array_slice($arr1,-1)[0];
		
		$c=array_slice($arr1,-2)[0];
		
		if(empty($c))
		{
			$c=$v;
			$v=null;
		}
		
		
		if(!empty($c) && strpos($c,'.')!=false)
		{
			$arr2=explode('.',$c);
			$c=$arr2[0];
		}
		
		if(!empty($c) && strtolower($c) =='index')
		{
			$c=null;
		}
		
		if(empty($c))
		{
			$c = isset($_GET['c'])?$_GET['c']:'home';
		}
		
		if(empty($v))
		{
			$v=isset($_GET['v'])?$_GET['v']:'index';
		}
		
		//return array($c,$v);
		
		self::loadController($c,$v);
	}
	/**
	* 加载控制器和视图
	*
	* @access globe
	*
	*/
	public static function loadController($c,$v)
	{
		$con = ucfirst(strtolower($c));
		$name='App\\Controller\\'.$con."Controller";
		$view=strtolower($v);
		if( class_exists($name))
		{
			$controller=new $name;
			if(method_exists($controller,$view))
			{
				$controller->$view();
			}else{
				self::out($view);
			}
		}else{
			self::out($name);
			self::out($view);
		}
	}


	//获取请求的网址
	public static function request_uri()
	{
		
		if ( isset($_SERVER['REQUEST_URI'] ) )
		{
			$uri = $_SERVER['REQUEST_URI'];
		}
		else
		{
			if ( isset( $_SERVER['argv'] ) )
			{
				$uri = $_SERVER['REQUEST_URI'];
			}
			else
			{
				if (isset($_SERVER['argv']))
				{
					$uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['argv'][0];
				}
				else
				{
					$uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING'];
				}
			}
			return $uri;
		}
	}
	
	

	/**
	* 获取真实IP
	*
	* @access globe
	*
	* @return 返回路径,
	*/
	public static function getIp(){  
		$realip = '';  
		$unknown = 'unknown';  
		if (isset($_SERVER)){  
			if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)){  
				$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);  
				foreach($arr as $ip){  
					$ip = trim($ip);  
					if ($ip != 'unknown'){  
						$realip = $ip;  
						break;  
					}  
				}  
			}else if(isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP']) && strcasecmp($_SERVER['HTTP_CLIENT_IP'], $unknown)){  
				$realip = $_SERVER['HTTP_CLIENT_IP'];  
			}else if(isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)){  
				$realip = $_SERVER['REMOTE_ADDR'];  
			}else{  
				$realip = $unknown;  
			}  
		}else{  
			if(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), $unknown)){  
				$realip = getenv("HTTP_X_FORWARDED_FOR");  
			}else if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), $unknown)){  
				$realip = getenv("HTTP_CLIENT_IP");  
			}else if(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), $unknown)){  
				$realip = getenv("REMOTE_ADDR");  
			}else{  
				$realip = $unknown;  
			}  
		}  
		$realip = preg_match("/[\d\.]{7,15}/", $realip, $matches) ? $matches[0] : $unknown;  
		return $realip;  
	}

	/**
	* 调试时的输出信息
	*
	* @access globe
	*
	* @param any $info 信息
	*/
	public static function out($info=null)
	{
		if( defined('DEBUG') && DEBUG )
		{
			$error = error_get_last();
			
			if( !empty($error) || !empty($info))
			{
				$error=error_get_last();
				
				if(!empty($error) || !empty($info))
				{
					echo "<div style=\"text-align:center;color:red;font-weight:700;font-size:20px\">";
					empty($error)?'':printf("错误提示：%s！在%s中第%u行。",$error['message'],$error['file'],$error['line']);
					empty($info)?'':var_dump($info);
					echo '</div>';
				}
			}
		}
	}
	
	/**
	* 写日志记录
	*
	* @access globe
	*
	* @param string|array $logs 信息
	*/
	public static function writeLog($logs)
	{
		$log = '';
		if(is_array($logs))
		{
			foreach($arr as $k => $r){
				$log .= "{$k}='{$r}',";
			}
		}else{
			$log = $logs;
		}
		$logFile = date('Y-m-d').'.txt';
		$log = date('Y-m-d H:i:s').' >>> '.$log."\r\n";
		file_put_contents($logFile,$log, FILE_APPEND );
	}

}