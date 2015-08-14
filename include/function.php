<?php
	/*****************************************************
	*全局方法
	*
	*
	*
	********************************************************/
	
	/**
	 * 加载未定义的数据类文件
	 *
	 * @access globe
	 *
	 */
	function __autoload($model)
	{
		$file=NWAYSMODEL.strtolower($model).NWAYSMF;
		if(is_file($file))
		{
			require_once($file);
		}
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
	/**
	 * 判断是否是首页
	 *
	 * @access globe
	 *
	 * @return true|false,
	 */
	function is_home()
	{
		$home=false;
		if(!isset($_GET['c']) || $_GET['c']=="home")
		{
			$home=true;
		}
		
		return $home;
		
	}
	
	/**
	 * 视图中包含其他视图
	 *
	 * @access globe
	 *
	 * @param string $name 文件名
	 */
	function extand($name){
		include(NWAYSVIEW."layout/".$name.".php");
	}

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
		$name=$con."Controller";
		$view=strtolower($v);
		
		$controllerfile=NWAYSCONTROLLER.$name.".php";
		
		if(is_file($controllerfile))
		{
			require_once($controllerfile);
			if( class_exists($name))
			{
				$controller=new $name;
				if(method_exists($controller,$view))
				{
					$controller->$view();
				}else{
					error404();
				}
			}else{
				error404();
			}
		}else{
			error404();
		}
		
	}

	//获取请求的网址
	function request_uri()
	{
		if (isset($_SERVER['REQUEST_URI']))
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




	/**
    * 网址生成
    * @access globe
	*
	* @param string $controller 控制器
	* @param string $view   方法
	* @param string $model  其他数据
	* @return 无返回值，输出网址,
    */
	function url($controller,$view=null,$model=null){
		$url="/";
		if($controller != "/")
		{
			$url="/?c={$controller}";
		}
		
		if(!empty($view))
		{
			$url.="&v={$view}";
		}
		
		if(!empty($model))
		{
			$url.="&{$model}";
		}
		return $url;
	}
	
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
	
	/**
    * 资源文件
    * @access globe
	*
	* @param string $name 不包括扩展名的文件名
	* @param string $kind  文件类型，默认是 js
    */
	function asset($name,$kind="js")
	{
		$tem="";
		switch($kind)
		{
			case "js":
				$tem='<script src="/asset/js/'.$name.'.js"></script>';
				break;
			case "css":
				$tem='<link type="text/css" rel="stylesheet" href="/asset/css/'.$name.'.css" />';
				break;
			case "favicon":
				$tem='<link href="/asset/img/'.$name.'.png" rel="shortcut icon"/>';
				break;
			default:
				break;
		}
		
		echo $tem;
	}
        
	/**
	 * 加载数据库操作类
	 *
	 * @access globe
	 *
	 * @param string $table 要操作的表
	 * @return 返回数据库操作对象,
	 */
	/*function pdo($table){
		require(NWAYSCLASS."pdo".NWAYSCF);
		return new PdoClass($table,);
	}*/
	
	 /**
	 * 加载微信操作类
	 *
	 * @access globe
	 *
	 * @return 返回微信操作对象,
	 */
	function WeChat(){
		require(NWAYSCLASS."wechat".NWAYSCF);
		return new WeChat(NWAYSCONF."config.php");
	}
	
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
		require(NWAYSCLASS."upload".NWAYSCF);
		return new Upload($file,$rand,NWAYSCONF."config.php");
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
	function verify($codelen=4,$width=150,$height=50)
	{
		require(NWAYSCLASS."verify".NWAYSCF);
		return new Verify($codelen,$width,$height,"asset/font/AcademyKiller.ttf");
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
	function qrcode($url,$refresh=false,$file='asset/img/qrcode.png',$logo=null)
	{
		
		if(file_exists($file) && !$refresh )
		{
			return $file;
		}
		
		include(NWAYSCLASS.'phpqrcode'.NWAYSCF); 
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
	
	/**
	 * 输出404页面
	 *
	 * @access globe
	 *
	 */
	function error404()
	{
		header( 'Content-Type:text/html;charset=utf-8 ');
		include(NWAYSVIEW."404.php");
		exit;
	}
	
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