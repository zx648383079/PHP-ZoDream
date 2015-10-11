<?php 
App::extend(array(
	'~layout'=>array(
		'head',
		'nav'
		)
	)
);
?>

<div class="container-fixed">
	<button class="create">发表</button>
	<table>
		<?php
		$data = App::ret('data');
		if(empty($data))
		{
			echo '暂无数据！';
		}else{
			echo '';
			foreach ($data as $value) {
				echo "<li>{$value['title']}</li>";
			}
		}
		?>
		<thead>
		<tr>
			<th>ID</th>
			<th>OpenId</th>
			<th>Name</th>
			<th>Update</th>
			<th>Create</th>
		</tr>
		</thead>
		<tbody>
		<tr>
		<td>1</td>
		<td>1</td>
		<td>1</td>
		<td>1</td>
		<td>1</td>
		</tr>
		<tr>
		<td>2</td>
		<td>2</td>
		<td>2</td>
		<td>2</td>
		<td>2</td>
		</tr>
		<tr>
		<td>3</td>
		<td>3</td>
		<td>3</td>
		<td>3</td>
		<td>3</td>
		</tr>
		</tbody>
		<tfoot>
		<tr>
			<th colspan="3">上一页 下一页</th>
		</tr>
		</tfoot>
	</table>
	<ul class="listbox">
		<?php
		$data = App::ret('data');
		if(empty($data))
		{
			echo "暂无数据！";
		}else{
			foreach ($data as $value) {
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
	<div class="head">新增纪录</div>
	<div class="body">
		<form>
			类型：<input type="radio" name="kind" value="0">支出<input type="radio" name="kind" value="1">收入<br>
			金额：<input type="text" name="money"><br>
			备注：<textarea name="content" rows="12"></textarea>
		</form>
	</div>
	<div class="foot">
		<button class="add">新增</button>
		<button class="close">关闭</button>
	</div>
</div>

<?php App::extend('~layout.foot');?>