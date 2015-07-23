<?php
	/*****************************************************
	*全局方法
	*
	*
	*作者：zx
	*2015/7/23
	********************************************************/
	
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
	
	//视图中用
	function extand($name){
		include(ZXV."layout/".$name.".php");
	}
	
	//加载数据库操作类
	function sql($table){
		require(ZXF."zxsql".ZXP);
		return new WeChat(ZXC."config.php");
	}
	//加载微信操作类
	function WeChat(){
		require(ZXF."wechat".ZXP);
		return new WeChat(ZXC."config.php");
	}
	
	//写日志
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