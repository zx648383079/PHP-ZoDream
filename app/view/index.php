<?php 

App::extend(array('~layout' => array('head','nav')));
?>

<div class="container-fixed">
	<?php 
	 $data = App::ret('data');
	 if(is_bool($data))
	 {
		 echo 'THIS IS ZODREAM!';
	 } else {
		 echo $data->content;
	 }
	?>
</div>

<?php App::extend('~layout.foot');?>