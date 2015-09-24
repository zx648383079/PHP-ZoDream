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
	
<div class="long">
			<?php
				$model = App::ret('model');
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
					echo '<p class="fail">执行失败！</p>';
				}else if($model == TRUE)
				{
					echo '<p class="success">执行成功！</p>';
				}
			?>
  <form method="post">
    <textarea name="sql" rows="10"><?php App::ech('sql');?></textarea>
    <button>执行</button>
  </form>
</div>

</div>
	
	
<?php App::extend('~layout.foot');?>