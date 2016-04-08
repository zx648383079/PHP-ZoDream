<!DOCTYPE HTML>
<html lang="ch-ZHS">
<head>
<title><?php $this->ech('title', 'ZoDream');?>-<?php $this->ech('tagline', 'ZoDream');?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Keywords" content="<?php $this->ech('keywords');?>" />
<meta name="Description" content="<?php $this->ech('description');?>" />
<meta name="author" content="<?php $this->ech('author');?>" />
<link rel="icon" href="<?php $this->asset('images/favicon.png');?>">
<?php $this->jcs(array(
	'fabric.min.css',
	'fabric.components.min.css',
	'zodream.css'
));?>
</head>
<body>
<!-- header -->
<div class="ms-NavBar">
	<div class="ms-NavBar-openMenu js-openMenu">
		<i class="ms-Icon ms-Icon--menu"></i>
	</div>
	<div class="ms-Overlay"></div>
	<ul class="ms-NavBar-items">
		<li class="ms-NavBar-item ms-NavBar-item--search ms-u-hiddenSm">
			<div class="ms-TextField">
				<input type="text" name="search" class="ms-TextField-field" title="搜索">
			</div>
		</li>
		<li class="ms-NavBar-item"><a class="ms-NavBar-link" href="<?php $this->url('/');?>">首页</a></li>

		<li class="ms-NavBar-item<?php $this->cas($this->hasUrl('product'), ' is-selected');?>"><a class="ms-NavBar-link" href="<?php $this->url('product');?>">产品</a></li>
		<li class="ms-NavBar-item<?php $this->cas($this->hasUrl('blog'));?>"><a class="ms-NavBar-link" href="<?php $this->url('blog');?>">博客</a></li>
		<li class="ms-NavBar-item<?php $this->cas($this->hasUrl('task'));?>"><a class="ms-NavBar-link" href="<?php $this->url('task');?>">任务</a></li>

		<li class="ms-NavBar-item ms-NavBar-item--right<?php $this->cas($this->hasUrl('about'));?>">
			<i class="ms-Icon ms-Icon--creditCardOutline"></i>
			<a class="ms-NavBar-link" href="<?php $this->url('about');?>">关于</a>
		</li>
	</ul>
</div>
<!-- header -->	