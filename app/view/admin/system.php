<?php 

App::$data['menu'] = 1;
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
  <?php
    $data = App::ret('data');
    if(!empty($data)) 
    {
      foreach ($data as $value) {
        echo $value['page'];
      }
    }
  ?>
</div>
</div>
	
	
<?php App::extend('~layout.foot');?>