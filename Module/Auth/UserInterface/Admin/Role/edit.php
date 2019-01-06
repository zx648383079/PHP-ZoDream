<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */

$this->title = $model->id > 0 ? '编辑' : '新增'. '角色';

$js = <<<JS
$('#perm-box').select2();
JS;

$this->registerJs($js, View::JQUERY_READY);
?>

<h1><?=$this->title?></h1>
<?=Form::open($model, './admin/role/save')?>
    <?=Form::text('name', true)?>
    <?=Form::text('display_name')?>
    <?=Form::textarea('description')?>
    <div class="input-group">
        <label>权限</label>
        <div>
            <select name="perms[]" id="perm-box" multiple style="width: 100%">
                <?php foreach($permission_list as $item):?>
                <option value="<?=$item->id?>" <?=in_array($item->id, $model->perm_ids) ? 'selected' : ''?>><?=$item->display_name?></option>
                <?php endforeach;?>
            </select>
        </div>
    </div>
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>