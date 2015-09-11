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
	

    	<div class="ms-Grid-col ms-u-md10">
			<h1 class="ms-font-su ms-fontColor-white ms-bgColor-themeDarker">Breadcrumb</h1>
			<div class="ms-Table">
				<div class="ms-Table-row">
					<span class="ms-Table-cell">File name</span>
					<span class="ms-Table-cell">Location</span>
					<span class="ms-Table-cell">Modified</span>
					<span class="ms-Table-cell">Type</span>
				</div>
				<div class="ms-Table-row is-selected">
					<span class="ms-Table-cell">File name</span>
					<span class="ms-Table-cell">Location</span>
					<span class="ms-Table-cell">Modified</span>
					<span class="ms-Table-cell">Type</span>
				</div>
			</div>
		</div>
		
	</div>
</div>
	
	
<?php App::extend('~layout.foot');?>