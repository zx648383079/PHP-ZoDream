<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$type_list = ['顺序练习', '随机练习', ''];
$this->title = $course->name.'-'.$type_list[$type];
$js = <<<JS
bindDo();
JS;
$this->extend('layouts/main')->registerJs($js);
?>
<div class="container">
    <ul class="path">
        <li>
            <a href="<?=$this->url('/')?>" class="fa fa-home"></a>
        </li><li>
            <a href="<?=$this->url('./')?>" >题库首页</a>
        </li><li class="active">
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
            <?php $this->extend('./item', ['question' => $item]);?>
        <?php endforeach;?>
    </div>
    <div class="pager">
        <?php if($previous_url):?>
        <a href="<?=$previous_url?>">上一页</a>
        <?php endif;?>
        <?php if($next_url):?>
        <a href="<?=$next_url?>">下一页</a>
        <?php else:?>
        <a href="<?=$this->url('./pager/check')?>">交卷</a>
        <?php endif;?>
    </div>
</div>