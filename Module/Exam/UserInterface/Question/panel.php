
<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<div class="question-item-box">
    <?php $this->extend('Pager/item', compact('question'));?>
</div>
<div class="tool-bar">
    <div class="btn-bar">
        <?php if($previous_url):?>
        <a class="left btn" href="<?=$previous_url?>">上一题</a>
        <?php endif;?>

        <a class="left btn" data-type="check" href="<?=$this->url('./question/check')?>">验证</a>

        <?php if($next_url):?>
        <a class="left btn" href="<?=$next_url?>">下一题</a>
        <?php endif;?>
        <button class="right" data-target=".sheet-panel">显示答题卡</button>
        <button class="right" data-target=".analysis-panel">显示详解</button>
    </div>
</div>

<div class="panel analysis-panel">
    <div class="panel-header">
        题目解析
    </div>
    <div class="panel-body">
        <?=empty($question['analysis']) ? '暂无解析' : $question['analysis']?>
    </div>
</div>
<div class="panel sheet-panel">
    <div class="panel-header">
        答题卡
    </div>
    <div class="panel-body">
        <ul>
            <?php foreach($cart_list as $item):?>
            <li class="<?=$item['right'] > 0 ? 'right' : ''?> <?=$item['right'] < 0 ? 'wrong' : ''?> <?=$item['active'] ? ' active' : ''?>" data-id="<?=$item['id']?>">
                <a href="<?=$this->url('./question', ['id' => $item['id']])?>"><?=$item['order']?></a>
            </li>
            <?php endforeach;?>
        </ul>
    </div>
</div>