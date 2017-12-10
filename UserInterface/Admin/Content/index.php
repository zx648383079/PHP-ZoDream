<?php
defined('APP_DIR') or exit();
use Zodream\Html\Bootstrap\PanelWidget;
use Zodream\Infrastructure\Support\Html;
use Zodream\Html\Bootstrap\TableWidget;
/** @var $this \Zodream\Domain\View\View */

$this->extend('layout/header');
echo PanelWidget::show(array(
    'head' => '模型管理 '. Html::a('添加', 'content/add', array('class' => 'btn btn-primary')),
    'body' => TableWidget::show(array(
        'data' => $data,
        'columns' => array(
            'id' => 'ID',
            'type' => array(
                'label' => '模型类型',
                'format' => array(
                    '内容模型',
                    '表单模型'
                )
            ),
            'name' => '名称',
            'tablename' => '数据表名',
            array(
                'key' => 'id',
                'label' => '操作',
                'format' => function($id) {
                    return Html::a('字段管理', ['content/field', 'modelid' => $id]).
                    Html::a('修改', ['content/add', 'id' => $id]).
                    Html::a('删除', ['content/delete', 'id' => $id]);
                }
            )
        )
    ))
))
?>






<?=$this->extend('layout/footer')?>