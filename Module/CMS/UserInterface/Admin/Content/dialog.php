<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Theme;
use Module\CMS\Domain\Repositories\SiteRepository;
/** @var $this View */
?>

<form data-type="ajax" action="<?=$this->url('./@admin/content/save')?>" method="post" class="form-table" role="form">
    <div class="input-group">
        <label>栏目</label>
        <select name="cat_id" class="form-control">
            <?php foreach($cat_menu as $item):?>
            <?php if($item['model_id'] == $model->id):?>
            <option value="<?=$item['id']?>" <?=$cat_id == $item['id'] ? 'selected': '' ?>>
                <?php if($item['level'] > 0):?>
                    ￂ<?=str_repeat('ｰ', $item['level'] - 1)?>
                <?php endif;?>
                <?=$item['title']?>
            </option>
            <?php endif;?>
            <?php endforeach;?>
        </select>
    </div>
    <?=Theme::text('title', $data['title'], '标题', '请输入标题', true)?>
    <?=Theme::text('keywords', $data['keywords'], '关键词', '请输入关键词')?>
    <?=Theme::select('status', [
        SiteRepository::PUBLISH_STATUS_DRAFT => '草稿',
        SiteRepository::PUBLISH_STATUS_POSTED => '发布',
    ], $data['status'], '状态')?>

    <?php if(!request()->isAjax()):?>
    <div class="btn-group">
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger">取消修改</a>
    </div>
    <?php endif;?>
    <input type="hidden" name="id" value="<?=$id?>">
    <input type="hidden" name="model_id" value="<?=$model->id?>">
    <input type="hidden" name="parent_id" value="<?=$data['parent_id']?>">
</form>