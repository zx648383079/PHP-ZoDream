<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */

$this->title = $model->id > 0 ? '编辑' : '新增'. '文章';

$js = <<<JS
    var ue = UE.getEditor('container');
    $(function () {
        $("select[name=type]").change(function () { 
            $("#source-box").toggle($(this).val() == 1);
        });
    });
JS;

$this->registerJsFile('/assets/ueditor/ueditor.config.js')
    ->registerJsFile('/assets/ueditor/ueditor.all.js')
    ->registerJs($js);
?>

<h1><?=$this->title?></h1>
<?=Form::open($model, './admin/blog/save')?>
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
                <?=Form::select('term_id', [$term_list], true)?>
                <?=Form::select('type', ['原创', '转载'], true)?>
                <?=Form::text('source_url')?>
                <?=Form::text('keywords')?>
                <?=Form::textarea('description')?>
                <div class="input-group">
                    <label>
                        <input value="1" name="comment_status" type="checkbox" <?=$model->comment_status || $model->id < 1 ? 'checked': ''?>> 是否允许评论
                    </label>
                </div>
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