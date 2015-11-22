<?php 
App::extend(array('~layout' => array('head')));
?>

<div>
	<canvas id="stage">
		
	</canvas>
</div>





<?php 
App::extend(array('~layout' => array('foot')), array(
	'https://code.createjs.com/createjs-2015.05.21.min',
	'zodream.game',
	function() {?>
<script type="text/javascript">
Zodream.App.main('stage');
</script>		
<?php }
));
?>