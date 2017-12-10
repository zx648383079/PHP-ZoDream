<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Support\Html;
/** @var $this \Zodream\Domain\View\View */
/** @var $page \Zodream\Html\Page */

$this->title = $title;
$this->extend([
	'layout/header',
	'layout/navbar'
]);
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


<?php $this->extend('layout/footer')?>