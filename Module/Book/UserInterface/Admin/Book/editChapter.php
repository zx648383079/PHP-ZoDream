<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '编辑章节';
$js = <<<JS
bindChapter();
JS;
$this->registerJs($js);
?>

<h1><?=$this->title?></h1>
<?=Form::open($model, './admin/book/save_chapter')?>
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
                <?=Form::text('keywords')?>
                <?=Form::textarea('description')?>
            </div>
            <div class="zd-tab-item">
                <textarea id="content-box" name="content" style="width: 100%;min-height: 500px"><?=$model->body ? $model->body->content : ''?></textarea>
                <div class="length-box">
                    已输入
                    <span>0</span>
                    个字符
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    <input type="hidden" name="book_id" value="<?=$model->book_id?>">
<?= Form::close('id') ?>
