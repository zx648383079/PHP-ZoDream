<!DOCTYPE html>
<html lang="<?php echo getLang(); ?>">
<head>

<meta name="viewport" content="width=device-width, initial-scale=1"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Find Me，找到我" />

<title><?php echo empty($title)?"主页":$title; ?></title>

<?php 
	asset('favicon','favicon');
	asset("zx","css");
 ?>

</head>
<body>
<?php 
	//首页显示的内容
	if(is_home()) { 
	?>

<?php } ?>

	<div class="navbar">
		<div class="brand">
			<img src="/asset/img/favicon.png" alt=""/>
		</div>
		<ul class="inline">
			<li><a href="<?php echo url('/'); ?>">主页</a></li>
			<li><a href="<?php echo url('message'); ?>">消息</a>
				<ul>
					<li><a href="#">普通消息</a></li>
					<li><a href="#">图文消息</a></li>
					<li><a href="">消息</a>
						<ul>
							<li><a href="#">普通消息</a></li>
							<li><a href="#">图文消息</a></li>
						</ul>
					</li>
					<li><a href="#">图文消息</a></li>
				</ul>
			</li>
			<li><a href="#">会员</a></li>
			<li><a href="#">回复</a></li>
		</ul>
	</div>