<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
use Infrastructure\Editor;
/** @var $this View */
$this->title = $model->id > 0 ? '栏目编辑' : '新增栏目';
$js = <<<JS
bindCat();
JS;
$this->registerJs($js);
?>
<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/category/save')?>
    <div class="tab-box">
        <div class="tab-header">
            <div class="tab-item active">
                基本
            </div>
            <div class="tab-item">
                详情
            </div>
            <div class="tab-item">
                模板
            </div>
        </div>
        <div class="tab-body">
            <div class="tab-item active">
                <?=Form::text('title', true)?>
                <?=Form::text('name', true)?>
                <?=Form::radio('type', ['内容', '单页', '外链'])?>
                <?=Form::select('model_id', [$model_list, ['-- 无 --']])?>
                <div class="input-group">
                    <label>上级</label>
                    <select name="parent_id" class="form-control">
                        <option value="0">-- 无上级分类 --</option>
                        <?php foreach($cat_list as $item):?>
                        <option value="<?=$item['id']?>" <?=$model->parent_id == $item['id'] ? 'selected': '' ?>>
                            <?php if($item['level'] > 0):?>
                                ￂ<?=str_repeat('ｰ', $item['level'] - 1)?>
                            <?php endif;?>
                            <?=$item['title']?>
                        </option>
                        <?php endforeach;?>
                    </select>
                </div>
                <?=Form::file('thumb')?>
                <?=Form::file('image')?>
                <?=Form::text('keywords')?>
                <?=Form::textarea('description')?>
                <?=Form::checkbox('groups', [$group_list, 'name', 'name'])->value(explode(',', (string)$model->groups))?>
                <?=Form::text('position')?>
            </div>
            <div class="tab-item">
                <?=Form::text('url')->after('<i class="tool-help" data-help="channel-url" title="查看帮助"></i>')?>
                <?= Editor::html('content', $model->content) ?>
            </div>
            <div class="tab-item">
                <?=Form::select('category_template', $template_list['channel'])->tip('栏目页，默认继承模型，文件夹为Category')?>
                <?=Form::select('list_template', $template_list['channel'])->tip('栏目文章搜索页，默认继承模型，文件夹为Category')?>
                <?=Form::select('show_template', $template_list['content'])->tip('文章详情页，默认继承模型，文件夹为Content')?>
                <?=Form::switch('setting.open_comment')?>
            </div>
        </div>
    </div>

    <div class="btn-group">
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    </div>
<?=Form::close('id')?>

<div class="dialog dialog-box url-dialog" data-type="dialog">
    <div class="dialog-header">
        <div class="dialog-title">选择生成的网址</div><i class="fa fa-close dialog-close"></i>
    </div>
    <div class="dialog-body">
        <div class="flip-tab-item active">
            <div class="column-item" data-next="linkage">
                <div class="icon">
                    <i class="fa fa-bars"></i>
                </div>
                <div class="content">
                    <h3>联动项</h3>
                </div>
            </div>
            <div class="column-item" data-next="channel">
                <div class="icon">
                    <i class="fa fa-table"></i>
                </div>
                <div class="content">
                    <h3>栏目</h3>
                </div>
            </div>
        </div>
        <div class="flip-tab-item">
            <form class="form-horizontal" role="form">
                <div class="input-group">
                    <label class="sr-only" for="keywords">标题</label>
                    <input type="text" class="form-control" name="keywords" id="keywords" placeholder="标题">
                </div>
                <button type="submit" class="btn btn-default">搜索</button>
            </form>
            <div class="list-scroll-body">
                <div class="option-list-item">
                    栏目1
                </div>
                <div class="option-list-item selected">
                    栏目1
                </div>
            </div>
        </div>
    </div>
    <div class="dialog-footer">
        <button type="button" class="dialog-yes">确认</button>
        <button type="button" class="dialog-close">取消</button>
    </div>
</div>

