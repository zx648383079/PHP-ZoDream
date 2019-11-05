<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '新建站点';
$js = <<<JS
bindEdit();
JS;
$this->registerJs($js);
?>
<?=Form::open($model, './admin/site/save')?>
    <?=Form::text('name', true)?>
    <?=Form::text('title', true)?>
    <?=Form::text('keywords')?>
    <?=Form::text('domain')?>
    <?=Form::file('thumb')?>
    <?=Form::textarea('description')?>
    <div class="theme-select">
        <?php foreach($theme_list as $item):?>
            <div class="theme-item<?=$item->id == $model->theme_id ? ' active' : ''?>" data-id="<?=$item->id?>">
                <div class="thumb">
                    <img src="<?=$this->url('./admin/theme/asset',
                        ['file' => $item['thumb']], false)?>" alt="">
                </div>
                <div class="name"><?=$item['name']?></div>
                <div class="desc"><?=$item['description']?></div>
            </div>
        <?php endforeach;?>
        <input type="hidden" name="theme_id" value="<?=$model->theme_id?>">
    </div>
    <button class="btn">保存</button>
<?= Form::close('id') ?>