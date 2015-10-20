<?php 
use App\Lib\Object\OTime;

App::$data['nav'] = 1;
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
      关于
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
      发表于：<?php echo is_bool($data)?'2015-10-14 11:23:50':OTime::to($data->udate); ?>
    </div>
  </div>
</div>

	
	
<?php App::extend('~layout.foot');?>