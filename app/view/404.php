<?php
	
use App\App;

App::extend('~layout.head');
?>

<div class="container">
			<div class="center">
				<div class="rotateAnima"><?php App::ech('code','404'); ?></div>
				<div class="fail"><?php App::ech('error'); ?></div>
			</div>
		</div>
	</div>
</div>

<?php App::extend('~layout.foot'); ?>