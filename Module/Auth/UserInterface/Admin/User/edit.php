<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;

/** @var $this View */
$this->title = $model->id > 0 ? '编辑' : '新增'. '用户';
$js = <<<JS
$('#role-box').select2();
JS;

$this->registerCssFile('@select2.min.css')
    ->registerJsFile('@select2.min.js')
    ->registerJs($js, View::JQUERY_READY);
?>

<h1><?=$this->title?></h1>
<?=Form::open($model, './admin/user/save')?>
    <?=Form::text('name', true)?>
    <?=Form::email('email', true)?>
    <?=Form::radio('sex', $model->sex_list)?>
    <?=Form::file('avatar')?>
    <?= Form::password('password', '', '新密码', '输入新密码', !!$model->id) ?>
    <?= Form::password('confirm_password', '', '确认密码', '确认密码', !!$model->id) ?>

    <div class="input-group">
        <label>角色</label>
        <div>
            <select name="roles[]" id="role-box" multiple style="width: 100%">
                <?php foreach($role_list as $item):?>
                <option value="<?=$item->id?>" <?=in_array($item->id, $model->role_ids) ? 'selected' : ''?>><?=$item->display_name?></option>
                <?php endforeach;?>
            </select>
        </div>
    </div>
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>