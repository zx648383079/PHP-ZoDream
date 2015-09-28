<?php 
App::extend(array('~layout' => array('head','nav')));
?>

<div class="soForm2">
	<form>
		<input type="text" name="s" value="<?php App::ech('s'); ?>">
		<button type="submit">搜索</button>
	</form>
</div>
<div class="container">
	<div class="short">
		<ul class="menu">
			<li class="active">全部</li>
			<li>PHP</li>
		</ul>
	</div>
	<div class="long">
		<ul class="listbox">
			<li class="panel">
				<h2 class="head">
				<a href="<?php App::url('?v=method'); ?>">关于</a>
				</h2>
				<div class="body">
				就是这样的
				</div>
			</li>
			<li class="panel">
				<h2 class="head">
				关于
				</h2>
				<div class="body">
				就是这样的
				</div>
			</li>
		</ul>
	</div>
</div>

<?php App::extend('~layout.foot');?>