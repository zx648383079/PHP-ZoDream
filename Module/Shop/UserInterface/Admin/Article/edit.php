<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '分类';
$js = <<<JS
    bindEdit();
    $(function () {
        $("select[name=type]").change(function () { 
            $("#source-box").toggle($(this).val() == 1);
        });
    });
JS;

$this->registerJs($js);
?>
<h1><?=$this->title?></h1>
<?=Form::open($model, './admin/article/save')?>
<div class="zd-tab">
        <div class="zd-tab-head">
            <div class="zd-tab-item active">
                基本
            </div>
            <div class="zd-tab-item">
                详情
            </div>
        </div>
        <div class="zd-tab-body">
            <div class="zd-tab-item active">
                <?=Form::text('title', true)?>
                <?=Form::select('cat_id', [$cat_list], true)?>
                <?=Form::file('thumb')?>
                <?=Form::text('keywords')?>
                <?=Form::textarea('description')?>
            </div>
            <div class="zd-tab-item">
                <script id="container" style="height: 400px" name="content" type="text/plain" required>
                    <?=$model->content?>
                </script>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>