<?php 
App::extend(array('~layout' => array('head')));
?>

<div>
	<canvas id="stage">
		
	</canvas>
</div>





<?php 
App::extend(array('~layout' => array('foot')), array(
	
	function() {?>
<script type="text/javascript">
zodream.App.main('stage');
</script>		
<?php }
));
?>