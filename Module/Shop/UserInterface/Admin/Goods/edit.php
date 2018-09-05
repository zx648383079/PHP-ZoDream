<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '编辑商品';
$js = <<<JS
    var ue = UE.getEditor('container');
JS;

$this->registerJsFile('/assets/ueditor/ueditor.config.js')
    ->registerJsFile('/assets/ueditor/ueditor.all.js')
    ->registerJs($js);
?>

<h1><?=$this->title?></h1>
<?=Form::open($model, './admin/goods/save')?>
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
                <?=Form::text('name', true)?>
                <?=Form::text('series_number')?>
                <?=Form::select('cat_id', [$cat_list], true)?>
                <?=Form::select('brand_id', [$brand_list], true)?>
                <?=Form::text('price', true)?>
                <?=Form::text('market_price')?>
                <?=Form::text('stock')?>
                <?=Form::text('keywords')?>
                <?=Form::textarea('description')?>
                <div class="input-group">
                    <label>
                        <input value="1" name="is_best" type="checkbox" <?=$model->is_best || $model->id < 1 ? 'checked': ''?>> 精品
                    </label>
                </div>
                <div class="input-group">
                    <label>
                        <input value="1" name="is_hot" type="checkbox" <?=$model->is_hot || $model->id < 1 ? 'checked': ''?>> 热门
                    </label>
                </div>
                <div class="input-group">
                    <label>
                        <input value="1" name="is_new" type="checkbox" <?=$model->is_new || $model->id < 1 ? 'checked': ''?>> 最新
                    </label>
                </div>
                <?=Form::file('thumb')?>
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
<?= Form::close('id') ?>
