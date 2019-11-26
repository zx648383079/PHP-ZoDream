<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '添加商品详情';
$url = $this->url('./@admin/', false);
$js = <<<JS
bindSecKill('{$url}', {$act_id}, {$time_id});
JS;
$this->registerJs($js);
?>
<div class="search">
    <a class="btn btn-success pull-right" data-type="goods" href="javascript:;">设置商品</a>
</div>
<table class="table table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>商品名称</th>
        <th class="auto-hide">商品价格</th>
        <th>秒杀价格</th>
        <th>秒杀数量</th>
        <th>限购数量</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <?php $this->extend('./goodsBody');?>
    </tbody>
</table>
<div align="center">
    <?=$model_list->getLink()?>
</div>

<div id="goods-dialog" class="dialog dialog-box" data-type="dialog">
    <div class="dialog-header">
        <div class="dialog-title">选择商品</div>
        <i class="fa fa-close dialog-close"></i>
    </div>
    <div class="dialog-body">

    </div>
    <div class="dialog-footer">
        <button type="button" class="dialog-yes">确认</button>
        <button type="button"
            class="dialog-close">取消</button>
    </div>
</div>