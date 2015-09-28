<?php 
use App\Lib\Role\RComma;

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
	  $roles = null;
	  foreach (App::ret('users') as $value)
	  {
		  $active = null;
		  if( $id == $value['id'])
		  {
			  $active = 'active';
			  $roles = $value['roles'];
		  }
			echo '<li class="',$active,'"><a href="?c=admin&v=users&id='.$value['id'].'">'.$value['name'].'</a></li>';
		} ?>
    </ul>
  </div>
  
  <div class="long">
	 <h3>权限</h3>
    <form action="<?php App::url('?c=admin&v=users&id='.$value['id']); ?>" method="POST">
      <?php
	  foreach (App::ret('roles') as $value) 
		{
			echo '<input type="checkbox" name="role[]" value="'.$value['id'].'" ',RComma::judge($value['id'],$roles)?'checked':'','>'.$value['name'].'<br>';
		} 
		?>
      <button>执行</button>
    </form>
  </div>

</div>
</div>
	
	
<?php App::extend('~layout.foot');?>