<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Html;
use Zodream\Domain\Html\Bootstrap\TableWidget;
/** @var $this \Zodream\Domain\View\View */
/** @var $page \Zodream\Domain\Html\Page */
$this->title = '';
$this->extend('layout/header');
?>
<div class="row">
	<div class="col-md-3 col-md-offset-2">
        <?=Html::a('新增', 'Term/add', ['class' => 'btn btn-primary'])?>
	</div>
</div>

<?=TableWidget::show([
    'page' => $page,
    'columns' => [
        'id' => 'Id',
        'name' => 'Name',
        'slug' => 'Slug',
        'group' => 'Group',
        [
            'label' => 'Action',
            'key' => 'id',
            'format' => function($id) {
                return Html::a('查看', ['Term/view', 'id' => $id]).
                    Html::a('编辑', ['Term/edit', 'id' => $id]).
                    Html::a('删除', ['Term/delete', 'id' => $id]);
            }
        ]
    ]
])?>

<?php$this->extend('layout/footer');
?>