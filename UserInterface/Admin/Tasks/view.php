<?php
use Zodream\Infrastructure\ObjectExpand\TimeExpand;
defined('APP_DIR') or exit();
$this->extend(array(
	'layout' => array(
		'head',
		'navbar'
	)), array(
		'@admin/css' => array(
			'custom.css'
		),
		'@ueditor/third-party/SyntaxHighlighter/shCoreDefault.css'
	)
);
$data = $this->get('task');
?>
	<div id="page-wrapper">
		<div class="graphs">
			<div class="xs">
				<h3><?php echo $data['name'];?></h3>
				<div class="tab-content">
					<div class="row">
						<div class="col-sm-2">ID：</div>
						<div class="col-sm-10">
							<?php echo $data['id'];?>
						</div>
						<div class="col-sm-2">名称：</div>
						<div class="col-sm-10">
							<?php echo $data['name'];?>
						</div>
						<div class="col-sm-2">状态：</div>
						<div class="col-sm-10">
							<?php $this->tag($data['status'], array('无', '待审核', '进行中', '维护中', '完成'));?>
						</div>
						<div class="col-sm-2">进度：</div>
						<div class="col-sm-10">
							<?php echo $data['progress'];?>%
						</div>
						<div class="col-sm-2">内容：</div>
						<div class="col-sm-10">
							<?php echo $data['content'];?>
						</div>
						<div class="col-sm-2">提交时间：</div>
						<div class="col-sm-10">
							<?php echo TimeExpand::format($data['cdate']);?>
						</div>
					</div>
					<div class="clearfix"> </div>
				</div>
				<div class="copy_layout">
					<p>Copyright &copy; 2015.ZoDream All rights reserved.</p>
				</div>
			</div>
		</div>
	</div>
	</div>

<?php
$this->extend(array(
	'layout' => array(
		'foot'
	)), array(
		'@admin/js' => array(
			'metisMenu.min',
			'custom'
		),
		'@ueditor/third-party/SyntaxHighlighter/shCore',
		function () {?>
			<<script type="text/javascript">
				$(function(){
					SyntaxHighlighter.all();
				});
			</script>
		<?php }
	)
);
?>