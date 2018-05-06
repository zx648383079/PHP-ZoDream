<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
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
JS;
$this->registerJs($js, View::JQUERY_READY);
$this->extend('../layouts/header');
?>

    <h1><?=$this->title?></h1>
    <form data-type="ajax" action="<?=$this->url('./admin/project/save')?>" method="post" class="form-table" role="form">
        <div class="input-group">
            <label>项目名称</label>
            <input name="name" type="text" class="form-control" placeholder="项目名称" value="<?=$model->name?>">
        </div>
        <div class="input-group">
            <label>项目描述</label>
            <textarea name="description" class="form-control" placeholder="备注信息"><?=$model->description?></textarea>
        </div>
        <div class="input-group">
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
                    <?php foreach($model->environments as $item):?>
                    <tr>
                        <td>
                            <input type="text" name="environment[name][]" value="<?=$item['name']?>">
                        </td>
                        <td>
                            <input type="text" name="environment[title][]" value="<?=$item['title']?>">
                        </td>
                        <td>
                            <input type="text" name="environment[domain][]" value="<?=$item['domain']?>">
                        </td>
                        <td>
                            <a href="javascript:void(0);" class="fa fa-plus js_addEnvBtn" data-title="添加环境" ></a>
                            <a href="javascript:void(0);" class="fa fa-trash-o js_deleteEnvBtn" data-title="删除环境"></a>
                        </td>
                    </tr>
                    <?php endforeach;?>
                    <tr>
                        <td>
                            <input type="text" name="environment[name][]">
                        </td>
                        <td>
                            <input type="text" name="environment[title][]" >
                        </td>
                        <td>
                            <input type="text" name="environment[domain][]">
                        </td>
                        <td>
                            <a href="javascript:void(0);" class="fa fa-plus js_addEnvBtn" data-title="添加环境"></a>
                            <a href="javascript:void(0);" class="fa fa-trash-o js_deleteEnvBtn" data-title="删除环境"></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
        <input type="hidden" name="id" value="<?=$model->id?>">
    </form>

<?php
$this->extend('../layouts/footer');
?>