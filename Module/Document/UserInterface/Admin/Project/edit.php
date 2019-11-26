<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = $model->id ?  '编辑项目:'.$model->name : '新建项目';
$js = <<<JS
// 新增环境
$("body").on('click', '.js_addEnvBtn',function (event) {
    event.stopPropagation();
    var trObj = $(this).closest('tr');
    trObj.before(trObj.clone(true)).find('input').val('');
});

//删除环境
$("body").on('click', '.js_deleteEnvBtn',function (event) {
    // 阻止事件冒泡
    event.stopPropagation();

    if($('.js_deleteEnvBtn').length <= 1){
        Dialog.tip('至少要保留一个环境域名')
        return false;
    }
    $(this).closest('tr').remove();
});
$("#type").change(function (e) { 
    $("#environment-box").toggle($(this).val() == 1);
});
JS;
$this->registerJs($js, View::JQUERY_READY);
?>

<h1><?=$this->title?></h1>
<?= Form::open($model, './@admin/project/save') ?>
    <?= Form::text('name', true) ?>
    <?php if(!$model->id):?>
    <?= Form::select('type', ['普通文档', 'API文档']) ?>
    <?php endif;?>

    <?= Form::select('status', ['公开', '私有']) ?>
    <?= Form::textarea('description') ?>
    
    <?php if(!$model->id || $model->type == 1):?>
    <div id="environment-box" class="input-group">
        <label>环境域名</label>
        <table>
            <thead>
                <tr>
                    <th style="width:20%">环境标识符</th>
                    <th style="width:20%">标识符备注</th>
                    <th style="width:50%">环境域名</th>
                    <th style="width:10%"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($model->environment as $item):?>
                <tr>
                    <td>
                        <input type="text" name="environment[name][]" value="<?=$item['name']?>" placeholder="例如：dev">
                    </td>
                    <td>
                        <input type="text" name="environment[title][]" value="<?=$item['title']?>" placeholder="例如：测试环境">
                    </td>
                    <td>
                        <input type="text" name="environment[domain][]" value="<?=$item['domain']?>" placeholder="例如：http://zodream.cn">
                    </td>
                    <td>
                        <a href="javascript:void(0);" class="fa fa-plus js_addEnvBtn" data-title="添加环境" ></a>
                        <a href="javascript:void(0);" class="fa fa-trash-o js_deleteEnvBtn" data-title="删除环境"></a>
                    </td>
                </tr>
                <?php endforeach;?>
                <tr>
                    <td>
                        <input type="text" name="environment[name][]" placeholder="例如：dev">
                    </td>
                    <td>
                        <input type="text" name="environment[title][]" placeholder="例如：测试环境">
                    </td>
                    <td>
                        <input type="text" name="environment[domain][]" placeholder="例如：http://zodream.cn">
                    </td>
                    <td>
                        <a href="javascript:void(0);" class="fa fa-plus js_addEnvBtn" data-title="添加环境"></a>
                        <a href="javascript:void(0);" class="fa fa-trash-o js_deleteEnvBtn" data-title="删除环境"></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php endif;?>


    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>