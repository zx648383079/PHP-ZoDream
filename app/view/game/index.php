<?php 
App::extend(array('~layout' => array('head', 'menu')));
?>

<div class="container mini">
	<ul class="pane">
		<li>
			<a href="<?php App::url('game/1');?>">
				<img src="<?php App::file('asset/img/1.jpg');?>">
				<h2>猫</h2>
				<p>这是</p>
			</a>
		</li>
		<li>
			<img src="<?php App::file('asset/img/1.jpg');?>">
			<h2>猫</h2>
			<p>这是hjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjh</p>
		</li>
		<li>
			<img src="<?php App::file('asset/img/1.jpg');?>">
			<h2>猫</h2>
			<p>这是</p>
		</li>
		<li>
			<img src="<?php App::file('asset/img/1.jpg');?>">
			<h2>猫</h2>
			<p>这是</p>
		</li>
		<li>
			<img src="<?php App::file('asset/img/1.jpg');?>">
			<h2>猫</h2>
			<p>这是</p>
		</li>
		<li>
			<img src="<?php App::file('asset/img/1.jpg');?>">
			<h2>猫</h2>
			<p>这是</p>
		</li>
	</ul>
</div>

<?php App::extend('~layout.foot');?>