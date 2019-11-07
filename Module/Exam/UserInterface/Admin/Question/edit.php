<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '题目';
?>
<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/question/save')?>
    <?=Form::text('title', true)?>
    <div class="input-group">
        <label>上级</label>
        <select name="parent_id">
            <option value="0">-- 无上级科目 --</option>
            <?php foreach($cat_list as $item):?>
            <option value="<?=$item['id']?>" <?=$model->parent_id == $item['id'] ? 'selected': '' ?>>
                <?php if($item['level'] > 0):?>
                    ￂ<?=str_repeat('ｰ', $item['level'] - 1)?>
                <?php endif;?>
                <?=$item['name']?>
            </option>
            <?php endforeach;?>
        </select>
    </div>
    <?=Form::file('image')?>
    <?=Form::select('easiness', range(1, 10))?>
    <?=Form::textarea('analysis')?>
    <?=Form::select('type', ['单选题', '多选题', '判断题', '简答题'])?>
    <h3>答案</h3>
    <div class="option-box">
        <?php if($model->type < 2):?>
        <?php foreach($option_list as $item):?>
        <div class="option-item">
            <div class="option-value">
                <input type="text" name="">
            </div>
            <select name="" id="">
                <option value="">文字</option>
                <option value="">图片</option>
            </select>
            <select name="" id="">
                <option value="">对</option>
                <option value="">错</option>
            </select>
        </div>
        <?php endforeach;?>
        <a href="javascript:;" class="add-option">+</a>
        <?php elseif ($model->type < 3):?>
        <select name="" id="">
                <option value="">对</option>
                <option value="">错</option>
        </select>
        <?php else:?>
          <textarea name="" id="" cols="30" rows="10"></textarea>
        <?php endif;?>
    </div>
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>