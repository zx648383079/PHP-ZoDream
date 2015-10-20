<?php 

App::$data['nav'] = 2;
App::extend(array(
	'~layout'=>array(
		'head',
		'nav'
		)
	)
);
?>
	

<div class="container-fixed">
  <div class="panel">
    <h2 class="head">
      下载
    </h2>
    <div class="body">
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
    <div class="foot">
      
    </div>
  </div>
</div>

	
	
<?php App::extend('~layout.foot');?>