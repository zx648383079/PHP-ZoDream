<?php 
use App\Main;	

Main::extend('~layout.head');
?>
	
	<div class="form">
		<img src="/({$img})" alt="二维码"/>
	</div>
	
<?php Main::extend('~layout.foot');?>