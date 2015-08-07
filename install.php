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
	{ ?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="" />
<title><?php echo $title; ?></title>
<link href="asset/img/favicon.png" rel="shortcut icon"/>
<link type="text/css" rel="stylesheet" href="/asset/css/zx.css" />
</head>
<body>
	
	<?php }
	
	function foot()
	{
		echo "<script src=\"/asset/js/zx.js\"></script></body></html>";
	}
	
	function step1()
	{ ?>
<div>
	<form action="?step=2" method="POST">
		<div class="row">
			数据库主机：<input type="text" name="host" value="localhost" required/>
		</div>
		<div class="row">
			端口：<input type="text" name="port" value="3306" required/>
		</div>
		<div class="row">
			数据库名：<input type="text" name="db" required/>
		</div>
		<div class="row">
			用户名：<input type="text" name="user" value="root" required/>
		</div>
		<div class="row">
			密码：<input type="password" name="pwd" required/>
		</div>
		<div class="row">
			确认密码：<input type="password" name="confirm" required/>
		</div>
		<div class="row">
			表前缀：<input type="text" name="prefix"/>
		</div>
		<div class="row">
			编码：<input type="text" name="enc" value="utf8" required/>
		</div>
		<input type="submit" value="下一步"/>
	</form>
</div>
	<?php }
	
	function step2()
	{
		echo "<p>正在加载数据……</p>";
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
			$text="<?php\nreturn array(
				\n\t'mysql'=>array(							//MYSQL数据库的信息
				\n\t\t'host'=>'{$config['host']}',                //服务器
				\n\t\t'port'=>'{$config['port']}',                //端口
				\n\t\t'database'=>'{$config['db']}',				//数据库
				\n\t\t'user'=>'{$config['user']}',						//账号
				\n\t\t'password'=>'{$config['pwd']}',					//密码
				\n\t\t'prefix'=>'{$config['prefix']}',					//前缀
				\n\t\t'encoding'=>'{$config['enc']}'					//编码
				\n\t)
				\n);";
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
				
				mysql_select_db($config['db'], $link);
			}
			//设置编码
			mysql_query("SET NAMES {$config['enc']}");
			//要创建的内容
			/***********************************************************/
			
			$tables=array(
				'users'=>'name varchar(20),pwd varchar(32)'
				
			);
			
			/***********************************************************/
			foreach($tables as $key=>$value)
			{
				createTable($key,$value,$config['prefix'],$link);
			}
			
			
			mysql_close($link);
			
			echo "<br/>数据库连接断开！<p><a href=\"/\">进入主页</a></p>";
			
		}
	}
	
	//创建表
	function createTable($table,$query,$prefix,$link){
		echo "<br/>";
		$tableComplete=$prefix.$table;
		$result =mysql_num_rows(mysql_query("SHOW TABLES LIKE '$tableComplete'",$link));
		if($result>0){
			echo $tableComplete."表已存在！";
		}else{
			$result=mysql_query("CREATE TABLE $tableComplete(
				id INT(10) not null AUTO_INCREMENT,
				$query,
				created datetime,
				primary key (id)
				) 
				charset =utf8;",$link);
			if($result){
				echo $tableComplete."表创建成功！";
			}else{
				echo $tableComplete."表创建失败！".mysql_error($link);
			}
		}
	
	}