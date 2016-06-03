<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\Response\View */
$this->extend(array(
	'layout' => array(
		'head',
        'navbar'
	))
);
?>
<div class="container">
    <table class="table table-hover">
		<thead>
		<tr>
			<th>编号</th>
			<th>名称</th>
			<th>更新时间</th>
			<th>相关公司</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($this->get('data', array()) as $item) {?>
			<tr>
				<td><?=$item[0]['code']?></td>
				<td><?=$item[0]['name']?></td>
				<td><?=$item[0]['update_at']?></td>
				<td>
					<?php foreach ($item[1] as $value) {?>
						<p><?=$value['company']?></p>
					<?php }?>
				</td>
			</tr>
		<?php }?>
		</tbody>
	</table>
</div>
<?php
$this->extend(array(
	'layout' => array(
		'foot'
	)), array(
    )
);
?>