<?php 
use App\App;	

App::extend(array('~layout' => array('head','nav')));
?>



<?php App::extend('~layout.foot');?>