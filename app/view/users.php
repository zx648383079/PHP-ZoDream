<?php 
use App\App;	

App::extend(array(
	'~layout'=>array(
		'head',
		'menu'
		)
	)
);
?>
		<div class="ms-Grid-col ms-u-md10">
			<h1 class="ms-font-su ms-fontColor-white ms-bgColor-themeDarker">Breadcrumb</h1>
		</div>
	</div>
	<div class="ms-Grid-row">
		<div class="ms-Grid-col ms-u-md3">
			<ul>
				<?php foreach (App::ech('roles',array()) as $value) {
					echo '<li><a href="'.$value['id'].'">'.$value['name'].'</a></li>';
				} ?>
			</ul>
		</div>

    	<div class="ms-Grid-col ms-u-md7">
			<form method="post">
				<?php foreach (App::ech('roles',array()) as $value) {
					echo '<div class="ms-ChoiceField"><input class="ms-ChoiceField-input" type="checkbox" value="'.$value['id'].'">';
					echo '<label class="ms-ChoiceField-field"><span class="ms-Label">'.$value['name'].'</span></label></div>';
				} ?>
				
			</form>
		</div>
		
		
		
	</div>
</div>
	
	
<?php App::extend('~layout.foot');?>