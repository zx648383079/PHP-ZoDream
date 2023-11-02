<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '满减/满送';
$js = <<<JS
bindDiscount();
JS;
$this->registerJs($js);
?>
<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/activity/discount/save')?>
    <?=Form::text('name', true)?>
    <?=Form::textarea('description')?>
    <?=Form::radio('scope_type', ['全部商品', '指定分类', '指定品牌', '指定商品'])?>
    <?=Form::text('scope')?>
    <div class="input-group">
        <label for="">优惠内容</label>
        <div class="">
            <span class="radio-label">
                <input type="radio" id="configure_type0" name="configure[type]" value="0" checked="">
                <label for="configure_type0">满_元优惠</label>
            </span><span class="radio-label">
                <input type="radio" id="configure_type1" name="configure[type]" value="1">
                <label for="configure_type1">满_件优惠</label>
            </span>
            <div>
                <p>订单满 <input type="text" class="form-control" name="configure[amount]"><span class="unit">元</span> </p>
                <p><input type="checkbox" name="configure[discount_type]" value="0">打<input type="text" class="form-control" name="configure[discount_value]" size="10" style="min-width: auto">折</p>
                <p><input type="checkbox" name="configure[discount_type]" value="1">减<input type="text" class="form-control" name="configure[discount_money]" size="10" style="min-width: auto">元</p>
                <p><input type="checkbox" name="configure[discount_type]" value="2">送赠品 <input type="text" class="form-control" name="configure[discount_goods]"></p>
                <p><input type="checkbox" name="configure[discount_type]" value="3">包邮</p>
            </div>
        </div>

    </div>
    

    <div class="input-group">
        <label for="start_at">起止时间</label>
        <div class="">
            <input type="text" id="start_at" class="form-control" name="start_at" autocomplete="off" placeholder="请输入开始时间" value="<?=$this->time($model->start_at)?>">
            ~
            <input type="text" id="end_at" class="form-control" name="end_at" placeholder="请输入结束时间" autocomplete="off" value="<?=$this->time($model->end_at)?>">
        </div>
    </div>
    <div class="btn-group">
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    </div>
<?= Form::close('id') ?>