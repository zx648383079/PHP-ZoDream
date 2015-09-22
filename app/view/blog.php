<?php 
use App\App;	

App::extend(array('~layout' => array('head','nav')));
?>

<div class="container">
	
</div>

<?php App::extend('~layout.foot');?>