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
                <?=Form::text('url')?>
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