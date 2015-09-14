<?php 
use App\App;	

App::extend(array(
	'~layout'=>array(
		'head',
		'nav'
		)
	)
);
?>


<div class="ms-Grid">
	<div class="ms-Grid-row">
		<div class="ms-Grid-col ms-u-sm0 ms-u-md2">
		</div>
	</div>
</div>

	
	
<?php App::extend('~layout.foot');?>