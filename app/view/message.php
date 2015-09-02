<?php 
use App\Main;	

Main::extend('~layout.head',array('fabric.css','fabric.components.css'));
?>
	
	<div class="container">
		
		<div>
			<table>
				<tr>
					<th>ID</th>
					<th>用户</th>
					<th>类型</th>
					<th>内容</th>
					<th>时间</th>
					<th>操作</th>
				</tr>
			</table>
		</div>

		
		<div>
			<form action="/message/add" method="POST">
				<div class="row">
					<input type="number" name="id"/>
				</div>
				<div class="row">
					<input type="text" name="type"/>
				</div>
				<div class="row">
					<textarea rows="4" name="content"></textarea>
				</div>
				<div class="row">
					<button type="submit">提交</button>
				</div>
			</form>
		</div>
	</div>
	
	

<?php
	Main::extend(
		'~layout.foot',
		array(
			'before' => array(
				'jquery',
				'jquery.fabric'
				)
			)
		);
	?>