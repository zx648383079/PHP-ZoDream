<?php 
App::extend(array('~layout' => array('head','nav')));
?>

<div class="soForm2">
	<form>
		<input type="text" name="s" value="<?php App::ech('s'); ?>">
		<button type="submit">搜索</button>
	</form>
</div>
<div class="container">
	<div class="short">
		<ul class="menu">
			<li class="active">全部</li>
			<?php
				foreach (App::ret('kind',array()) as $value) {
					echo "<li>{$value['name']}</li>";
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
	</div>
</div>

<?php App::extend('~layout.foot');?>