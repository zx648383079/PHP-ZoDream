<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '支付方式';
$js = <<<JS
bindPayment();
JS;

$this->registerJs($js, View::JQUERY_READY);
?>
<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/payment/save')?>
    <?=Form::text('name', true)?>
    <?=Form::file('icon')?>
    <?=Form::select('code', $pay_list, true)?>
    <?=Form::text('fee')?>
    <?=Form::textarea('description')?>
    <div class="input-group">
        <label>限定配送</label>
        <div>
            <select name="shipping[]" id="shipping-box" multiple style="width: 100%">
                <option value="0">不限</option>
                <?php foreach($shipping_list as $item):?>
                <option value="<?=$item->id?>"><?=$item->name?></option>
                <?php endforeach;?>
            </select>
        </div>
    </div>
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>