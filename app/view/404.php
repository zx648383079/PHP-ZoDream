<?php 
use App\Main;

	Main::$data['title'] = "404页面";
	Main::extend('~layout.head');
?>

<div class="ms-Grid">
	<div class="ms-Grid-row">
    	<div class="ms-Grid-col ms-u-sm12 ms-u-mdPush2 ms-u-md8 ms-u-lgPush3 ms-u-lg6">
			<h1>404</h1>
			<div class="error"><?php echo $error; ?></div>
		</div>
	</div>
</div>

<?php Main::extend('~layout.foot'); ?>