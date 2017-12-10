<?php
defined('APP_DIR') or exit();
use Zodream\Html\Bootstrap\PanelWidget;
use Zodream\Html\Bootstrap\FormWidget;
/** @var $this \Zodream\Domain\View\View */
$this->extend('layout/header');

echo PanelWidget::show(array(
    'head' => '添加模型',
    'body' => FormWidget::begin($data)
        ->hidden('id')
        ->select('type', array(
            '内容模型',
            '表单模型'
        ), array(
            'label' => '模型类型：',
        ))
        ->text('name', array('label' => '名称：'))
        ->text('tablename', array('label' => '数据表名：'))
        ->text('categorytpl', array('label' => '栏目模板：'))
        ->text('listtpl', array('label' => '列表模板：'))
        ->text('showtpl', array('label' => '列表模板：'))
        ->button()->end()
))
?>




<?=$this->extend('layout/footer')?>
