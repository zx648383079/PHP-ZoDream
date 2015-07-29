<?php
	require_once("include/function.php");
	
	
	$step=isset($_GET['step'])?$_GET['step']:1;
	
	switch($step)
	{
		case 1:
			head("第一步");
			step1();
			break;
		case 2:
			head("第二步");
			step2();
			write();
			break;
	}
	
	foot();
	
	function head($title)
	{
		echo "<!DOCTYPE html><html><head><meta name=\"viewport\" 
		content=\"width=device-width, initial-scale=1\"/>
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
		<meta name=\"keywords\" content=\"\" />
		
		<title>{$title}</title>
		<link href=\"/asset/img/favicon.png\" rel=\"shortcut icon\"/>
		
		<link type=\"text/css\" rel=\"stylesheet\" href=\"/asset/css/zx.css\" />
		
		</head>";
	}
	
	function foot()
	{
		echo "<script src=\"/asset/js/zx.js\"></script></body></html>";
	}
	
	function step1()
	{
		echo "<div><form action=\"?step=2\" method=\"POST\">
		数据库主机：<input type=\"text\" name=\"host\" value=\"localhost\" required/>
		数据库名：<input type=\"text\" name=\"db\" required/>
		用户名：<input type=\"text\" name=\"user\" value=\"root\" required/>
		密码：<input type=\"password\" name=\"pwd\" required/>
		确认密码：<input type=\"password\" name=\"confirm\" required/>
		表前缀：<input type=\"text\" name=\"prefix\"/>
		编码：<input type=\"text\" name=\"enc\" value=\"utf8\" required/>
		<input type=\"submit\" value=\"下一步\"/></form></div>";
	}
	
	function step2()
	{
		echo "正在加载数据……";
	}
	
	function write()
	{
		if(file_exists("include/conf/config.php"))
		{
			echo "配置文件已存在<a href=\"/\">进入主页</a>";
			return;
		}else{
			$file="include/conf/config.php";
			$config=$_POST;
			$text="<?php\r\nreturn array(
				\r\n\t'mysql'=>array(							//MYSQL数据库的信息
				\r\n\t\t'host'=>'{$config['host']}',                //服务器
				\r\n\t\t'database'=>'{$config['db']}',				//数据库
				\r\n\t\t'user'=>'{$config['user']}',						//账号
				\r\n\t\t'password'=>'{$config['pwd']}',					//密码
				\r\n\t\t'prefix'=>'{$config['prefix']}',					//前缀
				\r\n\t\t'encoding'=>'{$config['enc']}'					//编码
				\r\n\t)
				\r\n);";
			if(!file_put_contents($file,$text))
			{
				echo "配置文件写入失败，请注意文件夹的读写权限";
				return false;
			}
			
			$link = mysql_connect($config['host'], $config['user'], $config['pwd'])
       		or die ('Not connected : ' . mysql_error());
 
			// make foo the current db
			if(!mysql_select_db($config['db'], $link))
			{
				mysql_query("CREATE DATABASE {$config['db']}",$link);
				echo "数据库创建成功!";
			}
			
			mysql_close($link);
		}
	}
	
	
	function mysqli(){
	$mysql_info=include_once("config.php");
	
	$mysqli=new mysqli();
	$mysqli = mysqli_connect($mysql_info['mysql']['host'],
		$mysql_info['mysql']['user'],$mysql_info['mysql']['password'],$mysql_info['mysql']['database']);
	if ( $mysqli ) {
	         echo "数据库连接成功！";
	}else {
	         echo "数据库连接失败！";
	}
	
	$mysqli->query("SET NAMES utf8");
	createTable("users","email varchar(50) not null,pwd varchar(255) not null,created datetime");
	
	$mysqli->close();
	echo "<br/>数据库连接断开！";
	
	}
	
	//创建表
	function createTable($table,$query){
		global $mysql_info;    //调用全局变量
		global $mysqli;
		echo "<br/>";
		$tableComplete=$mysql_info['prefix'].$table;
		$result =mysqli_num_rows(mysqli_query($mysqli,"SHOW TABLES LIKE '$tableComplete'"));
		if($result>0){
			echo $tableComplete."表已存在！";
		}else{
			$result=$mysqli->query("CREATE TABLE $tableComplete(
				id INT(10) not null AUTO_INCREMENT,
				$query,
				primary key (id)
				) 
				charset =utf8;");
			if($result){
				echo $tableComplete."表创建成功！";
			}else{
				echo $tableComplete."表创建失败！".mysqli_error($mysqli);
			}
	}
	
	}