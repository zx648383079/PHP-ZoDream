<?php
defined('APP_DIR') or exit();
$this->extend(array(
	'layout' => array(
		'head',
		'navbar'
	))
);
$task = $this->get('task', array(
	'name' => null,
	'status' => null,
	'content' => null
));
?>

	<div id="page-wrapper">
		<div class="graphs">
			<div class="xs">
				<h3>新建</h3>
				<div class="tab-content">
					<div class="tab-pane active" id="horizontal-form">
						<form class="form-horizontal" action="<?php $this->url();?>" method="POST">
							<div class="form-group">
								<label for="focusedinput" class="col-sm-2 control-label">名称</label>
								<div class="col-sm-8">
									<input type="text" name="name" value="<?php echo $task['name'];?>" class="form-control1" id="focusedinput" placeholder="名称">
								</div>
							</div>
							<div class="form-group">
								<label for="focusedinput" class="col-sm-2 control-label">状态</label>
								<div class="col-sm-8">
									<select class="form-control1" name="status" required>
										<?php $this->swi($task['status'], ' selected');?>
										<option value="0"<?php $this->cas(0);?>>无</option>
										<option value="1"<?php $this->cas(1);?>>待审核</option>
										<option value="2"<?php $this->cas(2);?>>进行中</option>
										<option value="3"<?php $this->cas(3);?>>维护中</option>
										<option value="4"<?php $this->cas(4);?>>完成</option>
									</select>
								</div>
							</div>
							<?php if ($this->hasUrl('edit')) { ?>
							<div class="form-group">
								<label for="focusedinput" class="col-sm-2 control-label">进度</label>
								<div class="col-sm-8">
									<input type="text" name="progress" value="<?php echo $task['progress'];?>" class="form-control1" id="focusedinput" placeholder="进度">
								</div>
							</div>
							<?php } ?>
							<div class="form-group">
								<label for="focusedinput" class="col-sm-2 control-label">内容</label>
								<div class="col-sm-8">
									<textarea id="editor" name="content" class="" placeholder="关键字"><?php echo $task['content'];?></textarea>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-push-2 col-sm-3">
									<button type="submit" class="btn-success btn">保存</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="copy_layout">
				<p>Copyright &copy; 2015.ZoDream All rights reserved.</p>
			</div>
		</div>
	</div>
	<!-- /#page-wrapper -->
	</div>


<?php
$this->extend(array(
	'layout' => array(
		'foot'
	)), array(
		'@ueditor' => array(
			'ueditor.config',
			'ueditor.all.min'
		),
		function(){?>
			<script type="text/javascript">
				UE.getEditor("editor");
			</script>
		<?php }
	)
);
?>