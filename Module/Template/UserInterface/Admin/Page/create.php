<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '新建页面';
$js = <<<JS
bindEdit();
JS;
$this->registerJs($js);
?>
<?=Form::open($model, './@admin/page/save')?>
    <?=Form::text('name', true)?>
    <?=Form::text('title', true)?>
    <?=Form::text('keywords')?>
    <?=Form::file('thumb')?>
    <?=Form::textarea('description')?>
    <?=Form::radio('type', ['普通网页', 'WAP网页'])?>
    <?=Form::text('position')?>
    <?=Form::text('settings[cache_time]')->label('缓存时间')->value($model->setting('cache_time'))?>
    <div class="input-group">
        <label for="description">页面模板</label>
        <div class="theme-select">
            <?php foreach([$theme] as $item):?>
                <div class="theme-item<?=$item->id == $model->theme_page_id ? ' active' : ''?>" data-id="<?=$item->id?>">
                    <div class="thumb">
                        <img src="<?=$this->url('./@admin/theme/asset', ['folder' => $item->path, 'file' => $item->thumb], false)?>" alt="">
                    </div>
                    <div class="name"><?=$item['name']?></div>
                    <div class="desc"><?=$item['description']?></div>
                </div>
            <?php endforeach;?>
            <input type="hidden" name="theme_page_id" value="<?=$model->theme_page_id?>">
        </div>
    </div>
    
    <button class="btn">保存</button>
    <input type="hidden" name="site_id" value="<?=$model->site_id?>">
<?= Form::close('id') ?>