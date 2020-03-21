<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '组合';
$js = <<<JS
bindMix();
JS;
$this->registerJs($js);
?>
<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/activity/mix/save')?>
    <?=Form::text('name', true)?>
    <?=Form::textarea('description')?>
    <div class="group-box">
        <p class="text-center">组合明细</p>
        <table class="regional-table">
            <thead>
            <tr>
                <th width="42%">商品名称</th>
                <th>
                    组合数量
                </th>
                <th>分摊价格</th>
                <th>
                    成本价
                </th>
                <th>小计</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
                <?php foreach($configure['goods'] as $item):?>
                <tr>
                    <td>
                        <span><?=$item['goods']['name']?></span>
                        <input type="hidden" name="configure[goods_id][]" value="<?=$item['goods_id']?>"></td>
                    <td><input type="text" name="configure[amount][]" value="<?=$item['amount']?>"></td>
                    <td><input type="text" name="configure[price][]" value="<?=$item['price']?>"></td>
                    <td><?=$item['goods']['price']?></td>
                    <td class="subtotal"><?= $item['amount'] * $item['price']?></td>
                    <td><a href="javascript:;" data-action="del">删除</a></td>
                </tr>
                <?php endforeach;?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" class="text-left">
                        <a href="javascript:;" class="btn" data-action="add">添加</a>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    
    <?=Form::text('price')->label('组合价格')->value($configure['price'])?>
    <div class="input-group">
        <label for="start_at">起止时间</label>
        <div class="">
            <input type="text" id="start_at" class="form-control " name="start_at" autocomplete="off" placeholder="请输入开始时间" value="<?=$this->time($model->start_at)?>">
            ~
            <input type="text" id="end_at" class="form-control " name="end_at" placeholder="请输入结束时间" autocomplete="off" value="<?=$this->time($model->end_at)?>">
        </div>
    </div>
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>

<?php $this->extend('Admin/Goods/selectDialog');?>