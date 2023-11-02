<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Theme;
/** @var $this View */
?>
<?php if($model->type < 2):?>
<div class="input-group">
    <label for="">选项</label>
    <div>
        <?php foreach($option_list as $item):?>
        <div class="option-item">
            <div class="option-value">
                <input type="text" class="form-control" name="option[content][]" value="<?=$item['content']?>">
                <input type="hidden" name="option[id][]" value="<?=$item['id']?>">
            </div>
            <select name="option[type][]" class="form-control">
                <option value="0" <?=$item['type'] < 1 ? 'selected' : ''?>>文字</option>
                <option value="1" <?=$item['type'] > 0 ? 'selected' : ''?>>图片</option>
            </select>
            <select name="option[is_right][]" class="form-control">
                <option value="0" <?=$item['is_right'] < 1 ? 'selected' : ''?>>错</option>
                <option value="1" <?=$item['is_right'] > 0 ? 'selected' : ''?>>对</option>
            </select>
            <a href="javascript:;" class="remove-btn">x</a>
        </div>
        <?php endforeach;?>
        <?php for($i = count($option_list); $i < 4; $i ++):?>
        <div class="option-item">
            <div class="option-value">
                <input type="text" class="form-control" name="option[content][]">
                <input type="hidden" name="option[id][]">
            </div>
            <select name="option[type][]" class="form-control">
                <option value="0">文字</option>
                <option value="1">图片</option>
            </select>
            <select name="option[is_right][]" class="form-control">
                <option value="0">错</option>
                <option value="1">对</option>
            </select>
            <a href="javascript:;" class="remove-btn">x</a>
        </div>
        <?php endfor;?>
        <a href="javascript:;" class="add-option">+</a>
    </div>
</div>
<?php elseif ($model->type < 3):?>
<?=Theme::select('answer', ['错', '对'], intval($model->answer), '答案')?>
<?php else:?>
<?=Theme::textarea('answer', $model->answer, '答案')->tip('请在题目中用“____” 代替，如果需要计算请使用“@1+@2*3”等公式，填空题为一行一个')?>
<?php endif;?>