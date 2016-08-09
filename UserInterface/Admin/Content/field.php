<?php
defined('APP_DIR') or exit();
use Zodream\Domain\Html\Bootstrap\PanelWidget;
use Zodream\Infrastructure\Html;
use Zodream\Domain\Html\Bootstrap\TableWidget;
/** @var $this \Zodream\Domain\View\View */
$this->extend(array(
    'layout' => array(
        'head'
    ))
);
echo PanelWidget::show(array(
    'head' => '字段管理 '. Html::a('添加', 'content/addField/modelid/'.$this->gain('model'), array('class' => 'btn btn-primary')),
    'body' => TableWidget::show(array(
        'data' => $this->gain('data'),
        'columns' => array(
            'position' => '排序',
            'field' => '名称',
            'name' => '字段别名',
            'formtype' => '字段类别',
            'type' => '字段类型',
            '' => '字段索引',
            array(
                'key' => 'id,model_id',
                'label' => '操作',
                'format' => function($id, $modelid) {
                    Html::a('修改', 'content/addField/id/'.$id.'/modelid/'.$modelid).
                    Html::a('删除', 'content/deleteField/id/'.$id.'/modelid/'.$modelid);
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