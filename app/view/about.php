<?php 

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
      <p>本作目前属于开发阶段，欢迎加入开发及测试！</p>
      <p>本作属于免费开源程序！使用本作不受任何限制！</p>
    </div>
    <div class="foot">
      发表于：2015-10-01 11:23:50
    </div>
  </div>
</div>

	
	
<?php App::extend('~layout.foot');?>