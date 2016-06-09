<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Html;
/** @var $this \Zodream\Domain\Response\View */
/** @var $page \Zodream\Domain\Html\Page */
$this->extend(array(
	'layout' => array(
		'head',
        'navbar'
	))
);
$page = $this->get('page');
?>
<div class="container">
    <table class="table table-hover">
		<thead>
		<tr>
			<th>公司名称</th>
			<th>负责人</th>
			<th>产品</th>
			<th>需求</th>
			<th>更新时间</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($page->getPage() as $item) :?>
			<tr>
				<td><?=Html::a($item['name'], ['company/view', 'id' => $item['id']])?></td>
				<td><?=$item['charge']?></td>
				<td><?=$item['product']?></td>
				<td><?=$item['demand']?></td>
				<td><?php $this->time($item['update_at'])?></td>
			</tr>
		<?php endforeach;?>
		</tbody>
		<tfoot>
		<tr>
			<th>
				<?php $page->pageLink();?>
			</th>
		</tr>
		</tfoot>
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