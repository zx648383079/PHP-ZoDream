<?php 

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
  <div class="short fixed">
    <input type="text" placeholder="请输入关键字"/>
	<ul>
		<li><a href="#">aa</a></li>
		<li><a href="#">aa</a></li>
		<li><a href="#">aa</a>
			<ul>
				<li><a href="#">aa</a></li>
				<li><a href="#">aa</a></li>
				<li><a href="#">aa</a></li>
				<li><a href="#">aa</a></li>
				<li><a href="#">aa</a></li>
			</ul>
		</li>
		<li><a href="#">aa</a></li>
		<li><a href="#">aa</a></li>
	</ul>
  </div>
  <div class="long fixed">
    <div id="document">
		
	</div>
  </div>
</div>
	
	
<?php App::extend('~layout.foot');?>