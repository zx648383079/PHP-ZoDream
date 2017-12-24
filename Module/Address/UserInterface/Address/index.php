<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Html;
use Zodream\Html\Bootstrap\TableWidget;
/** @var $this \Zodream\Template\View */
/** @var $page \Zodream\Html\Page */
$this->title = '';
$this->extend('layout/header');
?>
<div class="row">
	<div class="col-md-3 col-md-offset-2">
        <?=Html::a('新增', 'Address/add', ['class' => 'btn btn-primary'])?>
	</div>
</div>

<?=TableWidget::show([
    'page' => $page,
    'columns' => [
        'id' => 'Id',
        'user_id' => 'User Id',
        'name' => 'Name',
        'tel' => 'Tel',
        'region_id' => 'Region Id',
        'address' => 'Address',
        'status' => 'Status',
        'create_at' => 'Create At',
        'update_at' => 'Update At',
        [
            'label' => 'Action',
            'key' => 'id',
            'format' => function($id) {
                return Html::a('查看', ['Address/view', 'id' => $id]).
                    Html::a('编辑', ['Address/edit', 'id' => $id]).
                    Html::a('删除', ['Address/delete', 'id' => $id]);
            }
        ]
    ]
])?>

<?php$this->extend('layout/footer');
?>