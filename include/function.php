<?php
	/*****************************************************
	*全局方法
	*
	*
	*
	********************************************************/
	
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
	 * 加载数据库操作类
	 *
	 * @access globe
	 *
	 * @param string $table 要操作的表
	 * @return 返回数据库操作对象,
	 */
	function pdo($table){
		require(NWAYSCLASS."pdo".NWAYSFILE);
		return new PdoClass($table,NWAYSCONF."config.php");
	}
	
	 /**
	 * 加载微信操作类
	 *
	 * @access globe
	 *
	 * @return 返回微信操作对象,
	 */
	function WeChat(){
		require(NWAYSCLASS."wechat".NWAYSFILE);
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
		require(NWAYSCLASS."upload".NWAYSFILE);
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
		require(NWAYSCLASS."verify".NWAYSFILE);
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
		
		include(NWAYSCLASS.'phpqrcode'.NWAYSFILE); 
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