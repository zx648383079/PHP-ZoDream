<?php 

use App\Lib\Html\HPager;
App::extend(array('~layout' => array('head','nav')));
$s = App::ret('s');
?>

<div class="soForm2">
	<form>
		<input type="text" name="s" value="<?php echo $s; ?>">
		<button type="submit">搜索</button>
	</form>
</div>
<div class="container">
	<div class="short">
		<ul class="menu">
			<li class="active"><a href="<?php App::url("?s=$s");?>">全部</a></li>
			<?php
				foreach (App::ret('kind',array()) as $value) {
					echo "<li><a href=\"".App::url("?s=$s&kind={$value['id']}",FALSE)."\">{$value['name']}</a></li>";
				}
			?>
		</ul>
	</div>
	<div class="long">
		<ul class="listbox">
			<?php 
				$data = App::ret('data');
				if(empty($data))
				{
					echo '<li>暂无数据</li>';
				}
				else {
					foreach ($data as $value) {
						echo "<li class=\"panel\">
								<h2 class=\"head\">
								<a href=\"".App::url('?v=method&id='.$value['id'] , FALSE)."\">{$value['title']}</a>
								</h2>
								<div class=\"body\">
								{$value['content']}
								</div>
							</li>";
					}
				}
			?>
		</ul>
		<?php HPager::make( array('index'=> App::ret('index') ,'total'=> App::ret('total') ,'max'=> App::ret('max') )); ?>		
	</div>
</div>

<?php App::extend('~layout.foot');?>