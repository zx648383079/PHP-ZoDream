<div class="side-menu">
	<ul>
		<li><a href="#">三楼</a></li>
		<li <?php App::isUrl('Game', 'class="active"');?>><a href="<?php App::url('game');?>">二楼</a></li>
		<li <?php App::isUrl('/', 'class="active"');?>><a href="<?php App::url('/');?>">一楼</a></li>
	</ul>
	<ul class="bottom">
		<li <?php App::isUrl('About', 'class="active"');?>><a href="<?php App::url('about');?>">关于</a></li>
	</ul>
</div>