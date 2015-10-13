<?php 
use App\Lib\Html\HPager;

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
	<table id="listbox">
		<thead>
			<tr>
				<th>ID</th>
				<th>Kind</th>
				<th>Money</th>
				<th>DateTime</th>
			</tr>
			</thead>
		<tbody>
			<?php
				$data = App::ret('data');
				if(empty($data))
				{
					echo '<tr><th colspan="4">暂无数据</th></tr>';
				}
				else {
					foreach ($data as $value) {
						echo '<tr><td>',$value['id'],'</td><td>',$value['kind']?'收入':'支出','</td><td>',
						$value['money'],'</td><td>',$value['happen'],'</td></tr>';
					}
				}
			?>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="4">
					<?php HPager::make( array('index'=> App::ret('index') ,'total'=> App::ret('total') ,'max'=> App::ret('max') )); ?>
				</th>
			</tr>
		</tfoot>
	</table>
</div>
<div class="shade"></div>
<div id="create" class="window">
	<div class="head">新增纪录</div>
	<div class="body">
		<form>
			<input type="hidden" name="id" value="0">
			类型：<input type="radio" name="kind" value="0" required>支出<input type="radio" name="kind" value="1" required>收入<br>
			金额：<input type="text" name="money" required><br>
			时间：<input type="text" name="happen" required><br>
			备注：<textarea name="mark" rows="12"></textarea>
		</form>
	</div>
	<div class="foot">
		<button class="add">新增</button>
		<button class="close">关闭</button>
	</div>
</div>

<div id="view" class="window">
	<div class="head">查看纪录</div>
	<div class="body">
		编号：<span class="id">1</span><br>
		类型：<span class="kind">支出</span><br>
		金额：￥<span class="money">0</span><br>
		时间：<span class="time">0</span><br>
		备注：<span class="mark"></span>
	</div>
	<div class="foot">
		<button class="edit">编辑</button>
		<button class="delele">删除</button>
		<button class="close">关闭</button>
	</div>
</div>

<?php App::extend('~layout.foot',array('before' => function(){
	echo '<script>var APP_URL= "',APP_URL,'";</script>';
}));?>