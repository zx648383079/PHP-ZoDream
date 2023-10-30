<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Theme;
use Zodream\Html\Form;
use Module\CMS\Domain\Repositories\SiteRepository;
/** @var $this View */
?>

<form data-type="ajax" action="<?=$this->url('./@admin/category/batch_save')?>" method="post" class="form-table" role="form">
    <div class="input-group">
        <label>上级</label>
        <select name="parent_id" class="form-control">
            <option value="0">-- 无上级分类 --</option>
            <?php foreach($cat_list as $item):?>
            <option value="<?=$item['id']?>">
                <?php if($item['level'] > 0):?>
                    ￂ<?=str_repeat('ｰ', $item['level'] - 1)?>
                <?php endif;?>
                <?=$item['title']?>
            </option>
            <?php endforeach;?>
        </select>
    </div>
    <?=Theme::select('model_id', [$model_list, ['-- 继承父级栏目 --']], '', '模型')?>
    <?=Theme::checkbox('groups', [$group_list, 'name', 'name'], '', '分组')->value(explode(',', (string)$model->groups))?>

    <?=Theme::select('category_template', $template_list['channel'], '', '分类模板')->tip('栏目页，默认继承模型，文件夹为Category')?>
    <?=Theme::select('list_template', $template_list['channel'], '', '列表模板')->tip('栏目文章搜索页，默认继承模型，文件夹为Category')?>
    <?=Theme::select('show_template', $template_list['content'], '', '详情模板')->tip('文章详情页，默认继承模型，文件夹为Content')?>
    <?=Theme::switch('open_comment', 0, '开启评论')?>



    <?=Theme::textarea('content', '', '栏目名', "多个以换行分割，子级前加 - \n例如: \n一级栏目\n- 二级栏目\n-- 三级栏目")->tip("多个以换行分割，子级前加 - \n例如: \n一级栏目\n- 二级栏目\n-- 三级栏目")?>

    <?php if(!request()->isAjax()):?>
    <div class="btn-group">
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger">取消修改</a>
    </div>
    <?php endif;?>
    <?= Form::token() ?>
</form>