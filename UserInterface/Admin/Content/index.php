<?php
defined('APP_DIR') or exit();
use Zodream\Domain\Html\Bootstrap\PanelWidget;
use Zodream\Infrastructure\Html;
use Zodream\Domain\Html\Bootstrap\TableWidget;
/** @var $this \Zodream\Domain\Response\View */
$this->extend(array(
    'layout' => array(
        'head'
    ))
);
echo PanelWidget::show(array(
    'head' => '模型管理 '. Html::a('添加', 'content/add', array('class' => 'btn btn-primary')),
    'body' => TableWidget::show(array(
        'data' => $this->gain('data'),
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
                    return Html::a('字段管理', 'content/field/modelid/'.$id).
                    Html::a('修改', 'content/add/id/'.$id).
                    Html::a('删除', 'content/delete/id/'.$id);
                }
            )
        )
    ))
))
?>






<?php
$this->extend(array(
    'layout' => array(
        'foot'
    ))
);
?>