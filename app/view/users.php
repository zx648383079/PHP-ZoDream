<?php 
use App\Main;	

Main::extend(array(
	'~layout'=>array(
		'head',
		'menu'
		)
	)
);
?>
	

    	<div class="ms-Grid-col ms-u-md10">
			<h1 class="ms-font-su ms-fontColor-white ms-bgColor-themeDarker">Breadcrumb</h1>
			<form method="post">
				<?php foreach (Main::ech('roles') as $value) {
					echo '<div class="ms-ChoiceField"><input class="ms-ChoiceField-input" type="checkbox" value="'.$value['id'].'">';
					echo '<label class="ms-ChoiceField-field"><span class="ms-Label">'.$value['name'].'</span></label></div>';
				} ?>
				
			</form>
		</div>
		
		
		
	</div>
</div>
	
	
<?php Main::extend('~layout.foot');?>