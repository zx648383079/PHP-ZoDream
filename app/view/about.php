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
          echo $data.content;
        }
        ?>
      <p>本作目前属于开发阶段，欢迎加入开发及测试！</p>
      <p>本作属于免费开源程序！使用本作不受任何限制！</p>
    </div>
    <div class="foot">
      发表于：<?php echo is_bool($data)?'2015-10-14 11:23:50':OTime::to($data.udate); ?>
    </div>
  </div>
</div>

	
	
<?php App::extend('~layout.foot');?>