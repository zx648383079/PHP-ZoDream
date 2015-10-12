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
		<thead>
			<tr>
				<th>ID</th>
				<th>Kind</th>
				<th>Money</th>
				<th>DateTime</th>
			</tr>
			</thead>
		<tbody>
			<tr>
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
			</tr>
			<tr>
				<td>3</td>
				<td>3</td>
				<td>3</td>
				<td>3</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="4">
					上一页 下一页
				</th>
			</tr>
		</tfoot>
	</table>
</div>
</div>
<div class="shade"></div>
<div class="window">
	<div class="head">新增纪录</div>
	<div class="body">
		<form>
			类型：<input type="radio" name="kind" value="0">支出<input type="radio" name="kind" value="1">收入<br>
			金额：<input type="text" name="money"><br>
			时间：<input type="text" id="datetime" name="datetime"><br>
			备注：<textarea name="content" rows="12"></textarea>
		</form>
	</div>
	<div class="foot">
		<button class="add">新增</button>
		<button class="close">关闭</button>
	</div>
</div>

<?php App::extend('~layout.foot');?>