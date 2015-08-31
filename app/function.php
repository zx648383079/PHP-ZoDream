<?php
	/*****************************************************
	*全局方法
	*
	*
	*
	********************************************************/
<<<<<<< HEAD
	if(!function_exists('app'))
	{
		function app()
		{
			
=======
	
	
	/**
	 * 获取配置文件
	 *
	 * @access globe
	 *
	 * @param string|null $key 要获取的配置名
	 * @return array,
	 */
	function config( $key = null )
	{
		$configs = require("app/conf/config.php");
		if( ! empty( $key ) )
		{
			$configs = $configs[$key];
>>>>>>> origin/master
		}
	}
	
<<<<<<< HEAD
	
	if(!function_exists('config'))
	{
		/**
		* 获取配置文件
		*
		* @access globe
		*
		* @param string|null $key 要获取的配置名
		* @return array,
		*/
		function config($key=null)
		{
			$configs=require("app/conf/config.php");
			if(!empty($key))
			{
				$configs=$configs[$key];
=======
	/**
	 * Smarty中加载的自定义方法 主要是加载 js、css 文件
	 *
	 * @access globe
	 *
	 * @param array $file 获取到的值
	 *
	 * @return string     返回的值即输出的值
	 */
	function jcs( $file )
	{
		$result = '';
		$file = $file['name'];
		$arr = explode( '.', $file );
		if( count($arr) == 1 )
		{
			$result = '<script src="/asset/js/'.$file.'.js"></script>';
		}else{
			switch( end($arr) )
			{
				case 'js':
					$result = '<script src="/asset/js/'.$file.'"></script>';
					break;
				case 'css':
					$result = '<link rel="stylesheet" type="text/css" href="/asset/css/'.$file.'"/>';
					break;
				default:
					$result = '<script src="/asset/js/'.$file.'.js"></script>';
					break;
>>>>>>> origin/master
			}
			return $configs;
		}
	}
	
<<<<<<< HEAD
	if(!function_exists('jcs'))
	{
		/**
		* Smarty中加载的自定义方法 主要是加载 js、css 文件
		*
		* @access globe
		*
		* @param array $file 获取到的值
		*
		* @return string     返回的值即输出的值
		*/
		function jcs($file)
		{
			$result='';
			$file=$file['name'];
			$arr=explode('.',$file);
			if(count($arr)==1)
			{
				$result= '<script src="/asset/js/'.$file.'.js"></script>';
			}else{
				switch(end($arr))
				{
					case 'js':
						$result= '<script src="/asset/js/'.$file.'"></script>';
						break;
					case 'css':
						$result= '<link rel="stylesheet" type="text/css" href="/asset/css/'.$file.'"/>';
						break;
					default:
						$result= '<script src="/asset/js/'.$file.'.js"></script>';
						break;
				}
			}
				
			return $result;
=======
	/**
	 * 跳转页面
	 *
	 * @access globe
	 *
	 * @param string $url 要跳转的网址
	 * @param int $time 停顿的时间
	 * @param string $msg 显示的消息.
	 */
	function redirect( $url, $time=0, $msg='' ) {
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
	            echo $msg;
	        }
	        exit();
	    } else {
	        $str    = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
	        if ($time != 0)
	            $str .= $msg;
	        exit($str);
	    }
		
	}
	/**
	 * 判断是否是首页
	 *
	 * @access globe
	 *
	 * @return true|false,
	 */
	function is_home()
	{
		$home = false;
		if( !isset($_GET['c']) || $_GET['c'] == "home" )
		{
			$home = true;
>>>>>>> origin/master
		}
	}
	
	if(!function_exists('redirect'))
	{
<<<<<<< HEAD
		/**
		* 跳转页面
		*
		* @access globe
		*
		* @param string $url 要跳转的网址
		* @param int $time 停顿的时间
		* @param string $msg 显示的消息.
		*/
		function redirect($url, $time=0, $msg='') {
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
	}
	
	if(!function_exists('is_home'))
	{
		/**
		* 判断是否是首页
		*
		* @access globe
		*
		* @return true|false,
		*/
		function is_home()
=======
		$url = request_uri();
	
		//  /c/v 取最后两个        /c.*/v       ?c= & v=
		
		$arr = explode( '?', $url );
		
		$arr1 = explode( '/', $arr[0] );
		
		$v = array_slice($arr1,-1)[0];
		
		$c = array_slice($arr1,-2)[0];
		
		if( empty( $c ) )
		{
			$c = $v;
			$v = null;
		}
		
		
		if( ! empty ($c) && strpos($c,'.') != false)
		{
			$arr2 = explode('.',$c);
			$c = $arr2[0];
		}
		
		if( !empty($c) && strtolower($c) =='index' )
		{
			$c = null;
		}
		
		if( empty($c) )
>>>>>>> origin/master
		{
			$home=false;
			if(!isset($_GET['c']) || $_GET['c']=="home")
			{
				$home=true;
			}
			
			return $home;
			
		}
<<<<<<< HEAD
	}
	
	if(!function_exists('getCAV'))
	{
		/**
		* 加载控制器和视图
		*
		* @access globe
		*
		*/
		function getCAV()
		{
			$url = request_uri();
		
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
					out($view);
				}
			}else{
				out($con);
				out($view);
			}
			
=======
		
		if( empty($v) )
		{
			$v = isset($_GET['v'])?$_GET['v']:'index';
		}
		
		//return array($c,$v);
		
		
		$con = ucfirst(strtolower($c));
		$name = 'App\\Controller\\'.$con."Controller";
		$view = strtolower($v);
		if( class_exists($name))
		{
			$controller = new $name;
			if( method_exists($controller, $view) )
			{
				$controller -> $view();
			}else{
				out( $view );
			}
		}else{
			out( $con );
			out( $view );
>>>>>>> origin/master
		}
	}
	

	if(!function_exists('request_uri'))
	{
<<<<<<< HEAD
		//获取请求的网址
		function request_uri()
		{
			if (isset($_SERVER['REQUEST_URI']))
=======
		if ( isset($_SERVER['REQUEST_URI'] ) )
		{
			$uri = $_SERVER['REQUEST_URI'];
		}
		else
		{
			if ( isset( $_SERVER['argv'] ) )
>>>>>>> origin/master
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
	
<<<<<<< HEAD

	if(!function_exists('getLang'))
	{
		/**
		* 获取语言类型
		* @access globe
		*
		* @return 返回语言,
		*/
		function getLang() {
			$language = $_SERVER ['HTTP_ACCEPT_LANGUAGE'];  
			preg_match_all ( "/[\w-]+/", $language, $language );  
			return $language [0] [0];
		} 
	}
	
	if(!function_exists('WeChat'))
	{
		/**
		* 加载微信操作类
		*
		* @access globe
		*
		* @return 返回微信操作对象,
		*/
		function WeChat(){
			return new App\Lib\WeChat(config("wecaht"));
		}
	}
	 
	if(!function_exists('upload'))
	{
		/**
		* 上传文件
		*
		* @access globe
		*
		* @param boolen $rand 是否随机生成文件名
		* @return 返回上传操作对象,
		*/
		function upload($rand=true)
		{
			return new App\Lib\Upload($file,$rand,config("wecaht"));
		}
	}
	
	if(!function_exists('verify'))
	{
		/**
		* 加载验证码
		*
		* @access globe
		*
		* @param int $codelen 验证码的长度.
		* @param int $width 验证码图片的宽度.
		* @param int $height 验证码图片的高度.
		* @return 返回验证码操作对象,
		*/
		function verify($codelen=4,$width=150,$height=50)
		{
			return new App\Lib\Verify($codelen,$width,$height,"asset/font/AcademyKiller.ttf");
		}
	}
	
	
	if(!function_exists('qrcode'))
	{
		/**
		* 生成二维码
		*
		* @access globe
		*
		* @param string $url 要生成的网址
		* @param string $file 要生成的路径
		* @param boolen $refresh 是否强制刷新.
		* @param string $logo    图标的路径 
		* @return 返回路径,
		*/
		function qrcode($url,$refresh=false,$file='asset/img/qrcode.png',$logo=null)
=======
	 /**
	 * 加载微信操作类
	 *
	 * @access globe
	 *
	 * @return 返回微信操作对象,
	 */
	function WeChat(){
		return new App\Lib\WeChat( config("wecaht") );
	}
	
	/**
	 * 上传文件
	 *
	 * @access globe
	 *
	 * @param boolen $rand 是否随机生成文件名
	 * @return 返回上传操作对象,
	 */
	function upload( $rand = true )
	{
		return new App\Lib\Upload( $file, $rand, config("wecaht") );
	}
	
	/**
	 * 加载验证码
	 *
	 * @access globe
	 *
	 * @param int $codelen 验证码的长度.
	 * @param int $width 验证码图片的宽度.
	 * @param int $height 验证码图片的高度.
	 * @return 返回验证码操作对象,
	 */
	function verify( $codelen = 4, $width = 150, $height = 50 )
	{
		return new App\Lib\Verify( $codelen, $width, $height, "asset/font/AcademyKiller.ttf");
	}
	
	
	 /**
	 * 生成二维码
	 *
	 * @access globe
	 *
	 * @param string $url 要生成的网址
	 * @param string $file 要生成的路径
	 * @param boolen $refresh 是否强制刷新.
	 * @param string $logo    图标的路径 
	 * @return 返回路径,
	 */
	function qrcode( $url, $refresh = false, $file = 'asset/img/qrcode.png', $logo = null)
	{
		
		if( file_exists( $file) && !$refresh )
>>>>>>> origin/master
		{
			
			if(file_exists($file) && !$refresh )
			{
				return $file;
			}
			
			$errorCorrectionLevel = 'L';//容错级别 
			$matrixPointSize = 12;//生成图片大小
			
			//生成二维码图片 
			QRcode::png($url,$file , $errorCorrectionLevel, $matrixPointSize, 2); 
			
			if(!empty($logo))
			{
				$QR = imagecreatefromstring(file_get_contents($file)); 
				$logo = imagecreatefromstring(file_get_contents($logo)); 
				$QR_width = imagesx($QR);//二维码图片宽度 
				$QR_height = imagesy($QR);//二维码图片高度 
				$logo_width = imagesx($logo);//logo图片宽度 
				$logo_height = imagesy($logo);//logo图片高度 
				$logo_qr_width = $QR_width / 5; 
				$scale = $logo_width/$logo_qr_width; 
				$logo_qr_height = $logo_height/$scale; 
				$from_width = ($QR_width - $logo_qr_width) / 2; 
				//重新组合图片并调整大小 
				imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, 
				$logo_qr_height, $logo_width, $logo_height); 
				ImagePng($QR,$file);
			}
			
			return $file;
		}
<<<<<<< HEAD
=======
		
		$errorCorrectionLevel = 'L';//容错级别 
		$matrixPointSize = 12;//生成图片大小
		
		//生成二维码图片 
		QRcode::png($url,$file , $errorCorrectionLevel, $matrixPointSize, 2); 
		
		if(!empty($logo))
		{
			$QR = imagecreatefromstring(file_get_contents($file)); 
			$logo = imagecreatefromstring(file_get_contents($logo)); 
			$QR_width = imagesx($QR);//二维码图片宽度 
			$QR_height = imagesy($QR);//二维码图片高度 
			$logo_width = imagesx($logo);//logo图片宽度 
			$logo_height = imagesy($logo);//logo图片高度 
			$logo_qr_width = $QR_width / 5; 
			$scale = $logo_width/$logo_qr_width; 
			$logo_qr_height = $logo_height/$scale; 
			$from_width = ($QR_width - $logo_qr_width) / 2; 
			//重新组合图片并调整大小 
			imagecopyresampled( $QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, 
			$logo_qr_height, $logo_width, $logo_height); 
			ImagePng( $QR, $file );
		}
		
		return $file;
>>>>>>> origin/master
	}
	 
	if(!function_exists('getIp'))
	{
		/**
		* 获取真实IP
		*
		* @access globe
		*
		* @return 返回路径,
		*/
		function getIp(){  
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
	}
	 
	if(!function_exists('out'))
	{
<<<<<<< HEAD
		/**
		* 调试时的输出信息
		*
		* @access globe
		*
		* @param any $info 信息
		*/
		function out($info=null)
		{
			if(defined('DEBUG') && DEBUG)
=======
		if( defined('DEBUG') && DEBUG )
		{
			$error = error_get_last();
			
			if( !empty($error) || !empty($info))
>>>>>>> origin/master
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
	
<<<<<<< HEAD
	if(!function_exists('writeLog'))
	{
		/**
		* 写日志记录
		*
		* @access globe
		*
		* @param string|array $logs 信息
		*/
		function writeLog($logs){
			$log='';
			if(is_array($logs))
			{
				foreach($arr as $k=>$r){
					$log.="{$k}='{$r}',";
				}
			}else{
				$log=$logs;
			}
			$logFile = date('Y-m-d').'.txt';
			$log = date('Y-m-d H:i:s').' >>> '.$log."\r\n";
			file_put_contents($logFile,$log,FILE_APPEND );
		}
	}
	
=======
	/**
	 * 写日志记录
	 *
	 * @access globe
	 *
	 * @param string|array $logs 信息
	 */
	function writeLog( $logs ){
		$log = '';
		if( is_array( $logs ) )
		{
			foreach( $arr as $k => $r ){
			     $log .="{$k}='{$r}',";
			}
		}else{
			$log = $logs;
		}
		$logFile = date('Y-m-d').'.txt';
		$log = date('Y-m-d H:i:s').' >>> '.$log."\r\n";
		file_put_contents( $logFile, $log, FILE_APPEND );
	}
>>>>>>> origin/master
