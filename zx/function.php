<?php
	/***************
	*全局方法
	*
	*
	*作者：zx
	*2015/7/22
	*/
	
	//跳转
	function go($url, $time=0, $msg='') {
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
	
	//验证是否登录
	function auth(){
		if(isset($_SESSION['user']))
		{
			return true;
		}else{
			return false;
		}
	}
	
	//视图中用
	function extand($name){
		include(ZXV."layout/".$name.".php");
	}
	
	
	function sql($table){
		require(ZXF."zxsql".ZXP);
		return new WeChat(ZXC."config.php");
	}
	
	function WeChat(){
		require(ZXF."wechat".ZXP);
		return new WeChat(ZXC."config.php");
	}