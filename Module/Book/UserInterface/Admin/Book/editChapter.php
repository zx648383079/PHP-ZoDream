<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '编辑章节';
?>

    <h1><?=$this->title?></h1>
    <form data-type="ajax" action="<?=$this->url('./admin/book/save_chapter')?>" method="post" class="form-table" role="form">
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
                    <div class="input-group">
                        <label>章节名</label>
                        <input name="title" type="text" class="form-control"  placeholder="输入章节名" value="<?=$model->title?>">
                    </div>
                    <div class="input-group">
                        <label>关键词</label>
                        <textarea name="keywords" class="form-control" placeholder="关键词"><?=$model->keywords?></textarea>
                    </div>
                    <div class="input-group">
                        <label>简介</label>
                        <textarea name="description" class="form-control" placeholder="简介"><?=$model->description?></textarea>
                    </div>
                    <div class="input-group">
                        <label>
                            <input value="1" name="comment_status" type="checkbox" <?=$model->comment_status || $model->id < 1 ? 'checked': ''?>> 是否允许评论
                        </label>
                    </div>
                </div>
                <div class="zd-tab-item">
                    <textarea name="content" style="width: 100%;min-height: 500px"><?=$model->body->content?></textarea>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
        <input type="hidden" name="id" value="<?=$model->id?>">
        <input type="hidden" name="book_id" value="<?=$model->book_id?>">
    </form>
