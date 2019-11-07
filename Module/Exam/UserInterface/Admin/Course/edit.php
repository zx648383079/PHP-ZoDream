<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '科目';
?>
<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/course/save')?>
    <?=Form::text('name', true)?>
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
    <?=Form::file('thumb')?>
    <?=Form::textarea('description')?>
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>