
<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = $model['id'] ? '编辑组件' : '新建组件';
?>

<?=Form::open($model, './@admin/market/my_save')?>

    <?=Form::text('name', true)?>
    <?=Form::radio('type', ['页面', '组件'])?>
    <div class="input-group">
        <label>分类</label>
        <select name="cat_id" class="form-control" required>
            <?php foreach($cat_list as $item):?>
            <option value="<?=$item['id']?>" <?=$model->cat_id == $item['id'] ? 'selected': '' ?>>
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
    <?=Form::text('price')?>
    <?=Form::file('path')?>

    <div class="btn-group">
        <button type="button" class="btn btn-success btn-save">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    </div>
<?= Form::close('id') ?>
