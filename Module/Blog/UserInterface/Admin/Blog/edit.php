<?php
use Zodream\Template\View;
/** @var $this View */

$this->title = $model->id > 0 ? '编辑' : '新增'. '文章';

$js = <<<JS
    var ue = UE.getEditor('container');
JS;

$this->extend('layouts/header')
    ->registerJsFile('/assets/ueditor/ueditor.config.js')
    ->registerJsFile('/assets/ueditor/ueditor.all.js')
    ->registerJs($js);
?>

    <h1><?=$this->title?></h1>
    <form data-type="ajax" action="<?=$this->url('./admin/blog/save')?>" method="post" class="form-table" role="form">
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
                        <label>标题</label>
                        <input name="title" type="text" class="form-control"  placeholder="输入形态名称" value="<?=$model->title?>">
                    </div>
                    <div class="input-group">
                        <label>分类</label>
                        <select name="term_id" required>
                            <option value="">请选择</option>
                            <?php foreach($term_list as $item):?>
                            <option value="<?=$item->id?>" <?=$item->id == $model->term_id ? 'selected' : ''?>><?=$item->name?></option>
                            <?php endforeach;?>
                        </select>
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
                    <script id="container" style="height: 400px" name="content" type="text/plain" required>
                        <?=$model->content?>
                    </script>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
        <input type="hidden" name="id" value="<?=$model->id?>">
    </form>


<?php
$this->extend('layouts/footer');
?>