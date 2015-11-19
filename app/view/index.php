<?php 
App::extend(array('~layout' => array('head')));
?>

<div class="container top">
	<div class="logo">
		<img src="<?php App::file('asset/img/logo.png');?>" alt="ZoDream">
	</div>
	<form action="<?php App::url('/');?>" method="get">
		<input type="text" name="search">
		<button type="submit">搜索</button>
	</form>
</div>

<?php App::extend('~layout.foot');?>