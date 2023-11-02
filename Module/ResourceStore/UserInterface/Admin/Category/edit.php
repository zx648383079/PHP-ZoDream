<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */

$this->title = $model->id > 0 ? '编辑' : '新增'. '分类';
?>

<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/category/save')?>
    <?=Form::text('name', true)?>
    <div class="input-group">
        <label>上级</label>
        <select name="parent_id">
            <option value="0">-- 无上级分类 --</option>
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
    <?=Form::text('keywords')?>
    <?=Form::textarea('description')?>
    <?=Form::file('thumb')?>

    <div class="btn-group">
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    </div>
<?= Form::close('id') ?>