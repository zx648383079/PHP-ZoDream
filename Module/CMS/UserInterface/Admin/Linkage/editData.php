<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
use Module\CMS\Domain\FuncHelper;
/** @var $this View */

$this->title = ($model->id > 0 ? '编辑' : '新增').'联动项';
?>

<div class="flex-slide-row">
    <h1><?=$this->title?></h1>
    <?php if(!empty($languageItems)): ?>
    <div class="locale-selector select--with-search">
        <div class="select-input">
            <i class="fa fa-language"></i>
            切换语言：<?= FuncHelper::selectedLanguage($languageItems) ?>
        </div>
        <div class="select-option-bar">
            <?php foreach($languageItems as $item):?>
                <?php if($item['selected']): ?>
                <a class="option-item selected">
                    <?= $item['name'] ?>
                </a>
                <?php else: ?>
                <a class="option-item" href="<?=$this->url('./@admin/linkage/create_data', ['linkage_id' => $item['id'], 'parent_id' => $model->parent_id, 'locale' => $model->id > 0 ? $model->id : $model->locale_group_id])?>">
                    <?= $item['name'] ?>
                </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
<?=Form::open($model, './@admin/linkage/save_data')?>
<form data-type="ajax" action="<?=$this->url('./@admin/linkage/save_data')?>" method="post" class="form-table" role="form">
    <?=Form::text('name', true)?>
    <?=Form::text('position', true)?>
    <?=Form::file('thumb')?>
    <?=Form::textarea('description')?>
    
    <div class="btn-group">
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    </div>

    <input type="hidden" name="linkage_id" value="<?=$model->linkage_id?>">
    <input type="hidden" name="parent_id" value="<?=$model->parent_id?>">
    <input type="hidden" name="locale_group_id" value="<?=$model->locale_group_id?>">
<?=Form::close('id')?>