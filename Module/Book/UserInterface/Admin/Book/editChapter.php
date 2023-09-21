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
<?=Form::open($model, './@admin/book/save_chapter')?>
    <div class="tab-box">
        <div class="tab-header">
            <div class="tab-item active">
                基本
            </div><div class="tab-item">
                详情
            </div>
        </div>
        <div class="tab-body">
            <div class="tab-item active">
                <?=Form::text('title', true)?>
                <?=Form::text('source')?>
                <?=Form::text('position')->size(4)?>
            </div>
            <div class="tab-item">
                <textarea id="content-box" name="content"><?=$model->body ? $model->body->content : ''?></textarea>
                <div class="length-box">
                    已输入
                    <span>0</span>
                    个字符
                    <input type="hidden" name="size" value="<?=$model->size?>">
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    <input type="hidden" name="book_id" value="<?=$model->book_id?>">
<?= Form::close('id') ?>
