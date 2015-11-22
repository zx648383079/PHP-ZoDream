<div class="footer">
  <p>Copyright (c)2015-2018 <a href="<?php App::url('/');?>">ZoDream</a></p>
</div>
<?php 
	App::jcs(
		'zodream',
		App::$response->getExtra()
	);
?>
</body>
</html>