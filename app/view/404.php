<?php 
use App\Main;

	Main::$data['title'] = "出错了！";
	Main::extend('~layout.head',function(){
		echo isset($meta)?$meta:'';
	});
?>

<div class="ms-Grid">
	<div class="ms-Grid-row">
    	<div class="ms-Grid-col ms-u-sm12 ms-u-mdPush2 ms-u-md8 ms-u-lgPush3 ms-u-lg6">
			<div class="error">
				<div class="head"><?php Main::ech('code','404'); ?></div>
				<div class="info"><?php Main::ech('error'); ?></div>
			</div>
		</div>
	</div>
</div>

<?php Main::extend('~layout.foot'); ?>