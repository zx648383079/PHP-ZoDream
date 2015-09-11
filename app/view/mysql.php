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
			<?php
				$model = App::ech('model',array());
				if(!empty($model) && is_array($model))
				{
					$str = '<table><tr>';
					foreach ($model[0] as $key => $value) {
						$str .= '<td>'.$key.'</td>';
					}
					$str .= '</tr>';
					foreach ($model as $value) {
						$str .= '<tr>';
						foreach ($value as $v) {
							$str .= '<td>'.$v.'</td>';
						}
						$str .= '</tr>';
					}
					$str .= '</table>';
					echo $str;
				}else if($model === FALSE)
				{
					echo '<p>执行失败！</p>';
				}else if($model == TRUE)
				{
					echo '<p>执行成功！</p>';
				}
			?>
			<form method="post">
				<div class="ms-TextField ms-TextField--multiline">
					<label class="ms-Label">查询</label>
					<textarea class="ms-TextField-field" name="sql" rows="10"><?php App::ech('sql');?></textarea>
				</div>
				<button class="ms-Button ms-Button--primary"><span class="ms-Button-label">执行</span></button>
			</form>
		</div>
		
	</div>
</div>
	
	
<?php App::extend('~layout.foot');?>