<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '订单发货';
?>
<h1><?=$this->title?></h1>
<div class="panel">
    <div class="panel-header">
        订单号：<?=$order->series_number?>
    </div>
    <div class="panel-body">
        <table class="table-hover">
            <thead>
                <tr>
                    <th>商品名称</th>
                    <th>货号</th>
                    <th>单价</th>
                    <th>数量</th>
                    <th>原价</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($goods_list as $goods):?>
                <tr>
                    <td>
                        <img src="<?=$goods->thumb?>" alt="" width="50" height="50" align="left">
                        <a href=""><?=$goods->name?></a> <br>
                        <small>白色</small>
                    </td>
                    <td><?=$goods->series_number?></td>
                    <td>
                        <span class="text-red"><?=$goods->price?></span>
                    </td>
                    <td>
                        <?=$goods->number?>
                    </td>
                    <td>
                        <span class="text-red"><?=$goods->total?></span>
                    </td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>
</div>

<?=Form::open($order, './@admin/order/save')?>
<div class="panel">
    <div class="panel-header">
        填写发货信息
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table-left">
                    <tbody>
                    <?php if($address):?>
                    <tr>
                        <th>收货人:</th>
                        <td><?=$address->name?></td>
                    </tr>
                    <tr>
                        <th>收货人手机:</th>
                        <td><?=$address->tel?></td>
                    </tr>
                    <tr>
                        <th>所在地:</th>
                        <td><?=$address->region_name?></td>
                    </tr>
                    <tr>
                        <th>收货地址:</th>
                        <td><?=$address->address?></td>
                    </tr>
                    <?php endif;?>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <?=Form::select('shipping_id', [$shipping_list], true)?>
                <?=Form::text('logistics_number', true)?>
                <input type="hidden" name="operate" value="shipping">
            </div>
        </div>

        <div class="text-center">
            <button class="btn btn-success">确认提交</button>
        </div>
    </div>
</div>
<?= Form::close('id') ?>