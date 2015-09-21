<?php
	
use App\App;
?>

<!DOCTYPE html>
<html lang="<?php App::ech('lang','zh-CN');?>">
<head>

<meta name="viewport" content="width=device-width, initial-scale=1"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="danmu" />

<title><?php App::ech('title');?></title>

<?php App::jcs(App::$extra,'dream.css');?>

</head>
<body>


