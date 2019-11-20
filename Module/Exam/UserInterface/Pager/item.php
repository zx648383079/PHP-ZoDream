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
        <?php 
        $i = -1;
        echo preg_replace_callback('/_{2,}/', function($match) use ($prefix, $question, &$i) {
            $i ++;
            return sprintf('<input type="text" name="%s[answer][]" style="width: %spx" value="%s">', $prefix, strlen($match[0]) * 20, 
            isset($question['your_answer']) && isset($question['your_answer'][$i]) ? $question['your_answer'][$i] : '');
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
        <input type="radio" name="<?=$prefix?>[answer]" value="<?=$option['id']?>" id="option_<?=$option['id']?>" <?= isset($question['your_answer']) && $question['your_answer'] == $option['id'] ? 'checked' : '' ?>>
        <?php else:?>
        <input type="checkbox" name="<?=$prefix?>[answer][]" value="<?=$option['id']?>" id="option_<?=$option['id']?>" <?= isset($question['your_answer']) && in_array($option['id'], $question['your_answer']) ? 'checked' : '' ?>>
        <?php endif;?>
        <span class="order"><?=$option['order']?>.</span>
        <label for="option_<?=$option['id']?>"><?=$option['content']?></label>
        </div>
        <?php endforeach;?>
    </div>
    <?php elseif ($question['type'] == 2):?>
    <div class="option-list">
       <?php foreach([1 => '对', 0 => '错'] as $val => $label):?>
       <input type="radio" name="<?=$prefix?>[answer]" value="<?=$val?>" id="option_<?=$question['id']?>_<?=$val?>" <?= isset($question['your_answer']) && $question['your_answer'] == $val ? 'checked' : '' ?>>
       <label for="option_<?=$question['id']?>_<?=$val?>"><?=$label?></label>
       <?php endforeach;?>
    </div>
    <?php elseif ($question['type'] == 3):?>
    <textarea name="<?=$prefix?>[answer]" rows="10"><?= isset($question['your_answer']) ? $question['your_answer'] : '' ?></textarea>
    <?php endif;?>
    <?php if(!empty($question['dynamic'])):?>
    <input type="hidden" name="<?=$prefix?>[dynamic]" value="<?=$question['dynamic']?>">
    <?php endif;?>
    <input type="hidden" name="<?=$prefix?>[id]" value="<?=$question['id']?>">
</div>