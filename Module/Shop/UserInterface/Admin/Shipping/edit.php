<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '配送方式';

$js = <<<JS
bindShipping();
JS;
$this->registerJs($js);
?>
<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/shipping/save')?>
    <?=Form::text('name', true)?>
    <?=Form::file('icon')?>
    <?=Form::select('code', $shipping_list, true)?>
    <?=Form::radio('method', ['按件数', '按重量'])?>
    <div class="group-box">
        <p class="text-center">配送区域及运费</p>
        <table class="regional-table">
            <tbody>
            <tr>
                <th width="42%">可配送区域</th>
                <th>
                    <span class="first">首件 (个)</span>
                </th>
                <th>运费 (元)</th>
                <th>
                    <span class="additional">续件 (个)</span>
                </th>
                <th>续费 (元)</th>
                <th>免费标准</th>
            </tr>
            <?php foreach($group_list as $item):?>
            <tr>
                <td class="am-text-left">
                    <p class="selected-content am-margin-bottom-xs"><?=$item->region_label?></p>
                    <p class="operation am-margin-bottom-xs"> <a class="edit" href="javascript:;">编辑</a> <a
                            class="delete" href="javascript:;">删除</a> </p>
                    <input type="hidden"
                        name="shipping[region][]" value="<?=implode(',', $item->region_ids)?>">
                    <input type="hidden" name="shipping[id][]" value="<?=$item->id?>">
                </td>
                <td> <input type="number" class="form-control" name="shipping[first_step][]" value="<?=$item->first_step?>" required=""></td>
                <td> <input type="number" class="form-control" name="shipping[first_fee][]" value="<?=$item->first_fee?>" required=""></td>
                <td> <input type="number" class="form-control" name="shipping[additional][]" value="<?=$item->additional?>"></td>
                <td> <input type="number" class="form-control" name="shipping[additional_fee][]" value="<?=$item->additional_fee?>"></td>
                <td> <input type="number" class="form-control" name="shipping[free_step][]" value="<?=$item->free_step?>"></td>
            </tr>
            <?php endforeach;?>

            <tr>
                <td colspan="6" class="am-text-left">
                    <a class="btn add-region"
                        href="javascript:;">
                        <i class="iconfont icon-dingwei"></i>
                        点击添加可配送区域和运费
                    </a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <?=Form::textarea('description')?>
    <?=Form::text('position')?>
    <div class="btn-group">
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    </div>
<?= Form::close('id') ?>

<div class="dialog dialog-box regional-choice" data-type="dialog">
    <div class="dialog-header">
        <div class="dialog-title">选择可配送区域</div><i class="fa fa-close dialog-close"></i>
    </div>
    <div class="dialog-body" style="height: 440px;">

    </div>
    <div class="dialog-footer">
        <button type="button" class="dialog-yes">确认</button>
        <button type="button" class="dialog-close">取消</button>
    </div>
</div>