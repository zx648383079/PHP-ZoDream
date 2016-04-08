<?php
use Zodream\Infrastructure\ObjectExpand\TimeExpand;
defined('APP_DIR') or exit();
$this->extend(array(
	'layout' => array(
		'head',
		'navbar'
	))
);
$page = $this->get('page');
?>
	<div id="page-wrapper">
		<div class="graphs">
			<div class="xs">
				<h3>所有列表</h3>
				<div class="row">
					<div class="mailbox-content">

						<table class="table">
							<thead>
							<tr>
								<th>ID</th>
								<th>名称</th>
								<th>状态</th>
								<th>进度</th>
								<th>用户</th>
								<th>修改时间</th>
								<th>提交时间</th>
								<th></th>
							</tr>
							</thead>
							<tbody>
							<?php foreach ($page->getPage() as $value) {?>
								<tr class="unread checked">
									<td>
										<?php echo $value['id'];?>
									</td>
									<td>
										<?php echo $value['name'];?>
									</td>
									<td>
										<?php echo $value['status'];?>
									</td>
									<td>
										<?php echo $value['progress'];?>%
									</td>
									<td>
										<?php echo $value['user'];?>
									</td>
									<td>
										<?php echo TimeExpand::format($value['update_at']);?>
									</td>
									<td>
										<?php echo TimeExpand::format($value['create_at']);?>
									</td>
									<td>
										<a href="<?php $this->url('tasks/view/id/'.$value['id']);?>">查看</a>
										<a href="<?php $this->url('tasks/edit/id/'.$value['id']);?>">编辑</a>
										<a href="<?php $this->url('tasks/delete/id/'.$value['id']);?>">删除</a>
									</td>
								</tr>
							<?php }?>
							</tbody>
						</table>
						<?php $page->PageLink();?>
					</div>
				</div>
				<div class="clearfix"> </div>
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
	))
);
?>