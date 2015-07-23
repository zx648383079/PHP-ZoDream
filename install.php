<?php
	$mysql_info=include_once("config.php");
	
	$mysqli=new mysqli();
	$mysqli = mysqli_connect($mysql_info['host'],
		$mysql_info['user'],$mysql_info['password'],$mysql_info['database']);
	if ( $mysqli ) {
	         echo "数据库连接成功！";
	}else {
	         echo "数据库连接失败！";
	}
	
	$mysqli->query("SET NAMES utf8");
	createTable("users","email varchar(50) not null,pwd varchar(255) not null,created datetime");
	
	$mysqli->close();
	echo "<br/>数据库连接断开！";
	
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