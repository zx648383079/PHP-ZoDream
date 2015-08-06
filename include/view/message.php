<?php extand("head"); ?>
	
	<div class="container">
		
		<div>
			<table>
				<tr>
					<th></th>
				</tr>
				<tr>
					<td></td>
				</tr>
			</table>
		</div>
		
		<div>
			<form action="<?php echo url("message","postMessage"); ?>" method="POST">
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