<?php 
use App\Main;	

Main::extend('~layout.head');
?>
	
	<div class="form">
		<img src="<?php Main::url('?c=image&v=qrcode'); ?>" alt="二维码"/>
	</div>
	
<?php Main::extend('~layout.foot');?>