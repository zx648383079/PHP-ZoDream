<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '栏目编辑';
$js = <<<JS
bindCat();
JS;
$this->registerJs($js);
?>
<h1><?=$this->title?></h1>
<?=Form::open($model, './admin/category/save')?>
    <div class="zd-tab">
        <div class="zd-tab-head">
            <div class="zd-tab-item active">
                基本
            </div>
            <div class="zd-tab-item">
                详情
            </div>
            <div class="zd-tab-item">
                模板
            </div>
        </div>
        <div class="zd-tab-body">
            <div class="zd-tab-item active">
                <?=Form::text('title', true)?>
                <?=Form::text('name', true)?>
                <?=Form::radio('type', ['内容', '单页', '外链'])?>
                <?=Form::select('model_id', [$model_list, ['-- 无 --']])?>
                <div class="input-group">
                    <label>上级</label>
                    <select name="parent_id">
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
                <?=Form::checkbox('groups', [$group_list, 'name', 'name'])->value(explode(',', $model->groups))?>
                <?=Form::text('position')?>
            </div>
            <div class="zd-tab-item">
                <?=Form::text('url')?>
                <script id="container" style="height: 400px" name="content" type="text/plain" required>
                    <?=$model->content?>
                </script>
            </div>
            <div class="zd-tab-item">
                <?=Form::text('category_template')?>
                <?=Form::text('list_template')?>
                <?=Form::text('show_template')?>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?=Form::close('id')?>