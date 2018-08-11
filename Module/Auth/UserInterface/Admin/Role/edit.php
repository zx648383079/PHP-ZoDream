<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = $model->id > 0 ? '编辑' : '新增'. '角色';

$js = <<<JS
$('#perm-box').select2();
JS;

$this->registerCssFile('@select2.min.css')
    ->registerJsFile('@select2.min.js')
    ->registerJs($js, View::JQUERY_READY);
?>

    <h1><?=$this->title?></h1>
    <form data-type="ajax" action="<?=$this->url('./admin/role/save')?>" method="post" class="form-table" role="form">
        <div class="input-group">
            <label>角色名</label>
            <div>
                <input name="name" type="text" class="form-control"  placeholder="输入角色名" required value="<?=$model->name?>">
            </div>
        </div>
        <div class="input-group">
            <label>别名</label>
            <div>
                <input name="display_name" type="text" class="form-control"  placeholder="输入别名" value="<?=$model->display_name?>">
            </div>
        </div>
        <div class="input-group">
            <label>简介</label>
            <textarea name="description" class="form-control" placeholder="简介"><?=$model->description?></textarea>
        </div>
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
        <input type="hidden" name="id" value="<?=$model->id?>">
    </form>