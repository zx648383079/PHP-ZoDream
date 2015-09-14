<?php 
use App\App;	

App::extend(array(
	'~layout'=>array(
		'head',
		'nav',
		'menu'
		)
	)
);
?>
	

    	<div class="ms-Grid-col ms-u-md10">
			<h1 class="ms-font-su ms-fontColor-white ms-bgColor-themeDarker">关于</h1>
			
		</div>
		
	</div>
</div>
	
	
<?php App::extend('~layout.foot');?>