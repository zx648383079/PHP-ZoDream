<?php extand("head"); ?>
	
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
					<?php foreach( $data as $value ){
						echo "<tr>";
						foreach( $value as $row ){
							echo "<td>{$row}</td>";
						}
						echo "<td><a href=\"".url('message','delMsg','id='.$value->id)."\">删除</a></td></tr>";
					} ?>
			</table>
		</div>

		
		<div>
			<form action="<?php echo url("message","addMsg"); ?>" method="POST">
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
	
	
	
<?php extand("foot"); ?>