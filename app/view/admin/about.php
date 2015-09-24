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
  <div class="panel">
    <h2 class="head">
      关于
    </h2>
    <div class="body">
      就是这样的
    </div>
    <div class="foot">
      发表于：2015-9-16 17:15:50
    </div>
  </div>
</div>

</div>
	
	
<?php App::extend('~layout.foot');?>