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
      下载
    </h2>
    <div class="body">
      <p>本作下载地址：<a href="https://github.com/zx648383079/zodream" target="_blank">GITHUB</a></p>
      <p>Composer 方式：
        <p class="code">php composer.phar require zodream/zodream dev-master</p>
       </p>
    </div>
    <div class="foot">
      
    </div>
  </div>
</div>

	
	
<?php App::extend('~layout.foot');?>