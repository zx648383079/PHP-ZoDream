<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$prefix = sprintf('question[%s]', $question['id']);
?>
<div class="question-item item-type-<?=$question['type']?> <?=$question['right'] > 0 ? 'item-right' : 'item-wrong'?>">
    <?php if($question['type'] < 3):?>
    <div class="title">
        <span class="order"><?=$question['order']?>.</span>
        <?=$question['title']?>
    </div>
    <?php else:?>
    <div class="title">
        <span class="order"><?=$question['order']?>.</span>
        <?php 
        $i = -1;
        echo preg_replace_callback('/_{2,}/', function($match) use ($prefix, $question, &$i) {
            $i ++;
            if (isset($question['your_answer'][$i]) && $question['your_answer'][$i] === $question['answer'][$i]) {
                return sprintf('<label class="right">%s</label>', $question['your_answer'][$i]);
            }
            return sprintf('<label class="%s" title="你的答案">%s</label><label class="right" title="参考答案">%s</label>', $question['right'] > 0 ? 'right' : 'wrong', isset($question['your_answer'][$i]) ? $question['your_answer'][$i] : '', $question['answer'][$i]);
        }, $question['title'])?>
    </div>
    <?php endif;?>
    <?php if(!empty($question['content'])):?>
    <div class="content">
        <?=$question['content']?>
    </div>
    <?php endif;?>
    <?php if(!empty($question['image'])):?>
    <div class="image">
        <img src="<?=$question['image']?>" alt="">
    </div>
    <?php endif;?>
    <?php if($question['type'] < 2):?>
    <div class="option-list">
        <?php foreach($question['option'] as $option):?>
        <div class="option-item">
        <?php if($question['type'] < 1):?>
        <?php if($option['id'] == $question['your_answer'] || $option['is_right']):?>
        <span class="order <?= $option['is_right'] ? 'right' : 'wrong' ?>"><?=$option['order']?>.</span>
        <?php else:?>
        <span class="order"><?=$option['order']?>.</span>
        <?php endif;?>
        <?php else:?>
        <?php if(in_array($option['id'], $question['your_answer']) || $option['is_right']):?>
        <span class="order <?= $option['is_right'] ? 'right' : 'wrong' ?>"><?=$option['order']?>.</span>
        <?php else:?>
        <span class="order"><?=$option['order']?>.</span>
        <?php endif;?>
        <?php endif;?>
        <label for="option_<?=$option['id']?>"><?=$option['content']?></label>
        </div>
        <?php endforeach;?>
    </div>
    <?php elseif ($question['type'] == 2):?>
    <div class="option-list">
        <?php foreach(['1' => '对', '0' => '错'] as $val => $label):?>
        <div class="option-item">
            <?php 
            $val .= '';
            if($val === $question['your_answer'] || $question['answer'] === $val):?>
            <span class="<?= $question['answer'] === $val ? 'right' : 'wrong' ?>"><?=$label?></span>
            <?php else:?>
            <span><?=$label?></span>
            <?php endif;?>
        </div>
       <?php endforeach;?>
    </div>
    <?php elseif ($question['type'] == 3):?>
    <div class="answer-text <?=$question['right'] > 0 ? 'right' : 'wrong'?>">
        <label>你的答案:</label>
        <textarea readonly><?=$question['your_answer']?></textarea>
    </div>
    <div class="answer-text right">
        <label>参考答案:</label>
        <textarea readonly><?=$question['answer']?></textarea>
    </div>
    <?php endif;?>
    <?php if(!empty($question['dynamic'])):?>
    <input type="hidden" name="<?=$prefix?>[dynamic]" value="<?=$question['dynamic']?>">
    <?php endif;?>
</div>