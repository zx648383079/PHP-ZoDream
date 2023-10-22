<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
use Zodream\Helpers\Json;
use Module\MessageService\Domain\Repositories\MessageProtocol;
/** @var $this View */

$this->title = $model->id > 0 ? '编辑模板' : '新增模板';
$js = <<<JS
var targetBar = $('.tab-header-bar');
$('textarea[name="content"]').on('change', function() {
    var match;
    var pattern = /\{(\w+)\}/g;
    var items = [];
    var content = this.value;
    targetBar.empty();
    while (null !== (match = pattern.exec(content))) {
        if (items.indexOf(match[1]) >= 0) {
            continue;
        }
        items.push(match[1]);
        targetBar.append('<div class="tab-item"><span>' + match[1] +'</span><i class="fa fa-times"></i><input type="hidden" name="data[]" value="'+ match[1] +'"></div>');
    }
});
JS;
$this->registerJs($js);
$filterKeys = $model['data'];
if (!is_array($filterKeys)) {
    $filterKeys = Json::decode($filterKeys);
}
?>

<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/template/save')?>
    <?=Form::text('name', true)->readonly($model->id > 0)?>
    <?=Form::text('title', true)?>
    <?=Form::text('target_no')?>
    <?=Form::radio('type', [MessageProtocol::TYPE_TEXT => 'TEXT', MessageProtocol::TYPE_HTML => 'HTML'])?>
    <?=Form::textarea('content')?>
    <div class="input-group">
        <label for="">字段</label>
        <div class="tab-header-bar">
            <?php foreach($filterKeys as $item):?>
            <div class="tab-item"><span><?= $item ?></span><i class="fa fa-times"></i><input type="hidden" name="data[]" value="<?= $item ?>"></div>
            <?php endforeach;?>
        </div>
    </div>

    <div class="btn-group">
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    </div>
<?= Form::close('id') ?>