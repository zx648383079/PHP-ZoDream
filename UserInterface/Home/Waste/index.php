<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Support\Html;
/** @var $this \Zodream\Domain\View\View */
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
			<th>编号</th>
			<th>名称</th>
			<th>更新时间</th>
			<th>相关公司</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($data as $item) :?>
			<tr>
				<td><?=Html::a($item[0]['code'], ['waste/view', 'id' => $item[0]['id']])?></td>
				<td><?=$item[0]['name']?></td>
				<td><?=$this->time($item[0]['update_at'])?></td>
				<td>
					<?php foreach ($item[1] as $value) :?>
						<p><?=Html::a($value['company'], ['waste/company', 'id' => $value['company_id']])?></p>
					<?php endforeach;?>
				</td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
</div>



<?php $this->extend('layout/footer')?>