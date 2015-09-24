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
	<button class="create">发表</button>
	<ul class="listbox">
		<?php
		if(empty(App::ret('data')))
		{
			echo "暂无数据！";
		}else{
			foreach (App::ret('data') as $value) {
				echo "<li>{$value['title']}</li>";
			}
		}
		?>
	</ul>
	 <div class="pager">
		<?php
			
			$page = App::ret('page');
			$total = App::ret('total');
			if( $page > 1)
			{
				echo '<a href="',App::url('?v=blog&page='.($page-1)),'">上一页</a>';
			}
			
			for ($i = 1 ; $i < $total; $i++) 
			{ 
				if( $i == $page )
				{
					echo "<span>$i</span>";
				}else{
					echo '<a href="',App::url('?v=blog&page='.$i),"\">$i</a>";
				}
				
			}
			
			if( $page < $total)
			{
				echo '<a href="',App::url('?v=blog&page='.($page+1)),'">下一页</a>';
			}
		?>
	</div>
</div>
</div>
<div class="shade"></div>
<div class="window">
	<div class="head">新增博客</div>
	<div class="body">
		<form>
			标题：<input type="text" name="title"><br>
			父级：<select name="pid">
				<option value="0">无</option>
				<option>分页</option>
				<option>就</option>
				<option>好</option>
			</select><br>
			内容：<textarea name="content" rows="12"></textarea>
		</form>
	</div>
	<div class="foot">
		<button class="add">新增</button>
		<button class="close">关闭</button>
	</div>
</div>

<?php App::extend('~layout.foot');?>