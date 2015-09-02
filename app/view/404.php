<?php 
use App\Main;

	Main::$data['title'] = "404页面";
	Main::extend('~layout.head');
?>

<div class="container">
	404
	<?php echo $error; ?>
</div>

<?php Main::extend('~layout.foot'); ?>