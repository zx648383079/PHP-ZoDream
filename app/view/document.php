<?php 
use App\Lib\Html\HTree;

App::$data['nav'] = 3;
App::extend(array(
	'~layout'=>array(
		'head',
		'nav'
		)
	)
);
?>
	

<div class="container">
  <div id="sotext" class="short fixed">
    <input type="text" name="text" placeholder="请输入关键字"/>
	<div id="viewtree" class="treebox">
	<?php HTree::make(App::ret('data')); ?>
	</div>
  </div>
  <div class="long fixed">
    <div id="document">
		
	</div>
  </div>
</div>
	
	
<?php App::extend('~layout.foot', 'document');?>