<!DOCTYPE HTML>
<html lang="zh-CN">
<head>
<title><?php $this->ech('title', 'ZoDream');?>-<?php $this->ech('tagline', 'ZoDream');?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Keywords" content="<?php $this->ech('keywords');?>" />
<meta name="Description" content="<?php $this->ech('description');?>" />
<meta name="author" content="<?php $this->ech('author');?>" />
	<link rel="icon" href="<?php $this->asset('images/favicon.png');?>">
<?php $this->jcs(array(
	'bootstrap.min.css',
	'style.css'
));?>
</head>
<body>
<!-- header -->
	<div class="container">
		<div class="header">
			<div class="logo">
				<a href="<?php $this->url('/');?>"><img src="<?php $this->asset('home/images/logo.png');?>" class="img-responsive" alt="ZoDream" /></a>
			</div>
			<div class="header-left">
				<div class="head-nav">
						<span class="menu"> </span>
					<ul class="cl-effect-1">
						<li><a href="<?php $this->url('/');?>">首页</a></li>
						<li<?php $this->cas($this->hasUrl('about'), ' class="active"');?>><a href="<?php $this->url('about');?>">关于</a></li>
						<li<?php $this->cas($this->hasUrl('services'));?>><a href="<?php $this->url('services');?>">服务</a></li>
						<li<?php $this->cas($this->hasUrl('product'));?>><a href="<?php $this->url('product');?>">产品</a></li>
						<li<?php $this->cas($this->hasUrl('blog'));?>><a href="<?php $this->url('blog');?>">博客</a></li>
						<li<?php $this->cas($this->hasUrl('task'));?>><a href="<?php $this->url('task');?>">任务</a></li>
						<li<?php $this->cas($this->hasUrl('download'));?>><a href="<?php $this->url('download');?>">下载</a></li>
						<li<?php $this->cas($this->hasUrl('contact'));?>><a href="<?php $this->url('contact');?>">联系</a></li>
							<div class="clearfix"></div>
					</ul>
				</div>
				<div class="search2">
					<form action="http://www.baidu.com/baidu" target="_blank">
						<input name="ie" type="hidden" value="utf-8">
						<input name="tn" type="hidden" value="SE_zzsearchcode_shhzc78w">
						<input type="text"  name="word" size="30" baiduSug="1">
						<input type="submit" value="">
					</form>
				</div>
					<div class="clearfix"> </div>
			</div>
				<div class="clearfix"> </div>
		</div>
	
<!-- header -->	