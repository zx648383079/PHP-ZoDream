<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$type_list = ['顺序练习', '随机练习', ''];
$this->title = $course->name.'-'.$type_list[$type];
$url = $this->url('./', false);
$js = <<<JS
bindDo('{$url}');
JS;
$this->extend('layouts/main')
    ->registerCssFile('@dialog.css')
    ->registerJsFile('@jquery.dialog.min.js')
    ->registerJsFile('@main.min.js')->registerJs($js);
?>
<div class="container">
    <ul class="path">
        <li>
            <a href="<?=$this->url('/')?>" class="fa fa-home"></a>
        </li><li>
            <a href="<?=$this->url('./')?>" >题库首页</a>
        </li><li>
            <a href="<?=$this->url('./course', ['id' => $course->id])?>" ><?=$course->name?></a>
        </li>
        <li class="active">
            <?=$type_list[$type]?>
        </li>
    </ul>
</div>
<div class="container">
    <div class="question-panel">
        <?php foreach($items as $item):?>
            <?php $this->extend(
                $pager->finished ? './view' : './item'
                , ['question' => $item]);?>
        <?php endforeach;?>
    </div>
    <div class="tool-bar">
        <?php if($previous_url):?>
        <a href="<?=$previous_url?>">上一页</a>
        <?php endif;?>
        <?php if($next_url):?>
        <a href="<?=$next_url?>">下一页</a>
        <?php elseif (!$pager->finished):?>
        <a data-type="ajax" href="<?=$this->url('./pager/check')?>">交卷</a>
        <?php endif;?>
    </div>
    <?php if($pager->finished):?>
    <div class="msg-bar">
        <span class="left">
            <span
                class="gray">答对：</span><span class="count-right"><?=$report['right']?>&nbsp;题</span>
        </span>
        <span
            class="left">
            <span class="gray">答错：</span>
            <span class="count-wrong"><?=$report['wrong']?>&nbsp;题</span>
            </span>
        <span class="left">
            <span
                class="gray">正确率：</span><?=$report['scale']?>%</span>
    </div>
    <div class="panel sheet-panel" style="display: block;">
        <div class="panel-header">
            答题卡
        </div>
        <div class="panel-body">
            <ul>
                <?php foreach($cart_list as $item):?>
                <?php if($item['right'] > 0):?>
                <li class="right" data-id="<?=$item['id']?>"><?=$item['order']?></li>
                <?php elseif ($item['right'] < 0):?>
                <li class="wrong" data-id="<?=$item['id']?>"><?=$item['order']?></li>
                <?php else:?>
                <li data-id="<?=$item['id']?>"><?=$item['order']?></li>
                <?php endif;?>
                <?php endforeach;?>
            </ul>
        </div>
    </div>
    <?php endif;?>

</div>