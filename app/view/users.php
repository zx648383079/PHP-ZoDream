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

<div class="long">
  <div class="short">
	<h3>用户</h3>
    <ul class="menu">
      <?php
	  $id = App::ret('id');
	  foreach (App::ret('users') as $value)
	  {
			echo '<li class="',( $id == $value['id'])?'active':null,'"><a href="?c=admin&v=users&id='.$value['id'].'">'.$value['name'].'</a></li>';
		} ?>
    </ul>
  </div>
  
  <div class="long">
	 <h3>权限</h3>
    <form action="<?php App::url('?c=admin&v=users'); ?>" method="POST">
		<input type="hidden" name="id" value="<?php App::ech('id'); ?>">
      <?php foreach (App::ret('roles') as $value) 
		{
			echo '<input type="checkbox" value="'.$value['id'].'">'.$value['name'].'<br>';
		} 
		?>
      <button>执行</button>
    </form>
  </div>

</div>
</div>
	
	
<?php App::extend('~layout.foot');?>