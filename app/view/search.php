<?php 
App::extend(array('~layout' => array('head', 'menu')));
?>

<div class="container mini">
	<div class="logo">
		<img src="<?php App::file('asset/img/logo.png');?>" alt="ZoDream">
	</div>
	<form action="<?php App::url('/');?>" method="get">
		<input type="text" name="search" value="<?php App::ech('search');?>">
		<button type="submit">搜索</button>
	</form>
	<ul class="list">
		<li>
			<div class="question">
				这是什么？
			</div>
			<div class="answer">
				这是做梦的地方！
			</div>
		</li>
		<li>
			<div class="question">
				这是什么？
			</div>
			<div class="answer">
				这是做梦的地方！
			</div>
		</li>
		<li>
			<div class="question">
				这是什么？
			</div>
			<div class="answer">
				这是做梦的地方！
			</div>
		</li>
		<li>
			<div class="question">
				这是什么？
			</div>
			<div class="answer">
				这是做梦的地方！
			</div>
		</li>
	</ul>
	<div class="pager">
		<a href="<?php App::url();?>">上一页</a>
		<a href="<?php App::url();?>">下一页</a>
	</div>
</div>

<?php App::extend('~layout.foot');?>