<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\View\View */
$this->extend('layout/head');
echo \Zodream\Infrastructure\Html::tag('div', 
    \Zodream\Domain\Html\Bootstrap\FormWidget::begin($data)
        ->hidden('id')
        ->select('type', array(
            'follow' => '关注回复',
            'default' => '默认回复',
            'keywords' => '关键词回复'
        ), array(
            'label' => '类型'
        ))
        ->text('name', array('label' => '名称'))
        ->radio('trigger_type', array(
            'label' => '匹配类型',
            'value' => 'equal',
            'text' => '全等'
        ))
        ->radio('trigger_type', array(
            'value' => 'contain',
            'text' => '包含'
        ))
        ->text('trigger_keyword', array(
            'label' => '关键字',
        ))
        ->textArea('content', array(
            'label' => '内容',
            'required' => true
        ))
        ->button()
        ->end(), array(
        'class' => 'container'
    ));
?>

<?=$this->extend('layout/foot')?>