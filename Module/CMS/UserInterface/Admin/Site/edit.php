<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */

$this->title = ($model->id > 0 ? '编辑' : '新增').'站点';
$js = <<<JS
bindSite();
JS;
$this->registerJs($js);
?>

<h1><?=$this->title?></h1>

<?=Form::open($model, './@admin/site/save')?>
    <?=Form::text('title', true)?>
    <?=Form::select('theme', [$themes, 'description', 'name'])?>
    <?=Form::text('match_rule')->tip('结构：“[https]://[host]/[path]” 指定域名必须在域名前加 “//”, 其他都为路径形式')?>
    <?=Form::text('language')->tip('具体语言请查看模板')?>
    <?=Form::file('logo')?>
    <?=Form::text('keywords')?>
    <?=Form::textarea('description')?>
    <?=Form::select('status', [0 => '下线', 5 => '上线'])?>

    <div class="btn-group">
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    </div>
<?=Form::close('id')?>