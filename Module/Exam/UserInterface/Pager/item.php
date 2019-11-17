<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$prefix = sprintf('question[%s]', $question['id']);
?>
<div class="question-item">
    <?php if($question['type'] < 3):?>
    <div class="title">
        <span class="order"><?=$question['order']?>.</span>
        <?=$question['title']?>
    </div>
    <?php else:?>
    <div class="title">
        <span class="order"><?=$question['order']?>.</span>
        <?=preg_replace_callback('/_{2,}/', function($match) use ($prefix) {
            return sprintf('<input type="text" name="%s[answer][]" style="width: %spx">', $prefix, strlen($match[0]) * 20);
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
        <input type="radio" name="<?=$prefix?>[answer]" value="<?=$option['id']?>" id="option_<?=$option['id']?>">
        <?php else:?>
        <input type="checkbox" name="<?=$prefix?>[answer][]" value="<?=$option['id']?>" id="option_<?=$option['id']?>">
        <?php endif;?>
        <span class="order"><?=$option['order']?>.</span>
        <label for="option_<?=$option['id']?>"><?=$option['content']?></label>
        </div>
        <?php endforeach;?>
    </div>
    <?php elseif ($question['type'] == 2):?>
    <div class="option-list">
        <input type="radio" name="<?=$prefix?>[answer]" value="1">对
        <input type="radio" name="<?=$prefix?>[answer]" value="0">错
    </div>
    <?php elseif ($question['type'] == 3):?>
    <textarea name="<?=$prefix?>[answer]" rows="10"></textarea>
    <?php endif;?>
    <?php if(!empty($question['dynamic'])):?>
    <input type="hidden" name="<?=$prefix?>[dynamic]" value="<?=$question['dynamic']?>">
    <?php endif;?>
</div>