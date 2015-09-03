<?php 
use App\Main;	

Main::extend(array(
	'~layout'=>array(
		'head',
		'menu'
		)
	)
);
?>
	
	  <h1 class="ms-font-su ms-fontColor-white ms-bgColor-themeDarker">这里是测试页</h1>

	
	
<?php Main::extend('~layout.foot');?>