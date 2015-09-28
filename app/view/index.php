<?php 
App::extend(array('~layout' => array('head','nav')));
?>

<div class="soForm">
	<form>
		<input type="text" name="s">
		<button type="submit">搜索</button>
	</form>
</div>

<?php App::extend('~layout.foot');?>