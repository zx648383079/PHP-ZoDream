<?php
	
use App\App;

App::extend('~layout.head',function(){
		echo isset($meta)?$meta:'';
	});
?>

<!DOCTYPE html>
<html lang="<?php App::ech('lang','zh-CN');?>">
<head>

<meta name="viewport" content="width=device-width, initial-scale=1"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Find Me，找到我" />

<title><?php App::ech('title');?></title>

<?php App::jcs(App::$extra,'fabric.css','fabric.components.css','zx.css');?>

</head>
<body>

<div class="ms-Grid">
	<div class="ms-Grid-row">
    	<div class="ms-Grid-col ms-u-sm12 ms-u-mdPush2 ms-u-md8 ms-u-lgPush3 ms-u-lg6">
			<div class="error">
				<div class="head"><?php App::ech('code','404'); ?></div>
				<div class="info"><?php App::ech('error'); ?></div>
			</div>
		</div>
	</div>
</div>

<?php App::extend('~layout.foot'); ?>