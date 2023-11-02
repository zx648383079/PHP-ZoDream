<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */

$this->title = $model->id > 0 ? '编辑' : '新增'. '服务';
$js = <<<JS
bindEditService();
JS;
$this->registerJs($js);
?>

<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/service/save')?>
    <?=Form::text('name', true)?>
    <?=Form::select('cat_id', [$cat_list])?>
    <?=Form::text('price', true)?>
    <?=Form::file('thumb')?>
    <?=Form::textarea('brief')?>
    <?=Form::textarea('content')?>

    <table class="form-table">
        <thead>
            <tr>
                <th>名称</th>
                <th>别名</th>
                <th>是否必填</th>
                <th>不公开</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($model->form as $item):?>
            <tr>
                <td>
                    <input type="text" class="form-control" name="form[name][]" value="<?=$item['name']?>">
                </td>
                <td>
                    <input type="text" class="form-control" name="form[label][]" value="<?=$item['label']?>">
                </td>
                <td>
                    <input type="checkbox" name="form[required][]" value="1" <?=isset($item['required']) ? 'checked' : '' ?>>
                </td>
                <td>
                    <input type="checkbox" name="form[only][]" value="1" <?=isset($item['only']) ? 'checked' : '' ?>>
                </td>
                <td>
                    <i class="fa fa-times"></i>
                </td>
            </tr>
            <?php endforeach;?>
            <tr>
                <td>
                    <input type="text" class="form-control" name="form[name][]">
                </td>
                <td>
                    <input type="text" class="form-control" name="form[label][]">
                </td>
                <td>
                    <input type="checkbox" name="form[required][]" value="1">
                </td>
                <td>
                    <input type="checkbox" name="form[only][]" value="1">
                </td>
                <td>
                    <i class="fa fa-times"></i>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td>
                    <i class="fa fa-plus"></i>
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="btn-group">
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    </div>
<?= Form::close('id') ?>