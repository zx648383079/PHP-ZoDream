<?php 
App::extend(array('~layout' => array('head','nav')));
?>

<div class="soForm">
	<form>
		<input type="text" name="s">
		<button type="submit">搜索</button>
	</form>
</div>

<div class="img">
	<img src="asset/img/1.jpg">
	<img src="asset/img/2.jpg">
	<img src="asset/img/3.jpg">
	<img src="asset/img/1.jpg">
	<img src="asset/img/2.jpg">
	<img src="asset/img/3.jpg">
	<img src="asset/img/1.jpg">
	<img src="asset/img/2.jpg">
	<img src="asset/img/3.jpg">
<div>

<?php App::extend('~layout.foot');?>