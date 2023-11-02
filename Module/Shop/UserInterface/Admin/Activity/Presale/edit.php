<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '预售';
$js = <<<JS
bindPresale();
JS;
$this->registerJs($js);
?>
<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/activity/presale/save')?>
    <?=Form::text('goods_id', true)?>
    <?=Form::text('name')?>
    <div class="input-group">
        <label for="start_at">定金时间</label>
        <div class="">
            <input type="text" id="start_at" class="form-control " name="start_at" placeholder="请输入开始时间" value="<?=$this->time($model->start_at)?>">
            ~
            <input type="text" id="end_at" class="form-control " name="end_at" placeholder="请输入结束时间" value="<?=$this->time($model->end_at)?>">
        </div>
    </div>
    <div class="input-group">
        <label for="configure_start_at">尾款时间</label>
        <div class="">
            <input type="text" id="configure_start_at" class="form-control " name="configure[final_start_at]" placeholder="请输入开始时间" value="<?=$this->time($model->start_at)?>">
            ~
            <input type="text" id="configure_end_at" class="form-control " name="configure[final_end_at]" placeholder="请输入结束时间" value="<?=$this->time($model->end_at)?>">
        </div>
    </div>
    
    <?=Form::text('configure[ship_at]')->label('发货时间')?>
    <?=Form::radio('configure[price_type]', ['一口价', '阶梯价'])->label('预算模式')?>
    <div class="price_type_0">
        <?=Form::text('configure[price]')->label('预售价格')?>
    </div>
    <div class="input-group price_type_1" style="display: none">
        <label>预售价格</label>
        <div class="">
            <p class="step-item">
                满<input type="text" name="configure[step][amount]" value="1" size="10">人，预售价<input type="text" name="configure[step][price]" size="10">元
            </p>
            <a href="javascript:;" class="btn" data-action="add">
                <i class="fa fa-plus"></i>
            </a>
        </div>
    </div>

    <?=Form::text('configure[deposit]')->label('定金')?>
    <?=Form::radio('configure[deposit_scale]', [1 => '无', '1.5' => '1.5倍', 2 => '2倍', 99 => '自定义'])->label('定金膨胀系数')->after('<input type="text" class="form-control " name="configure[deposit_scale_other]" placeholder="自定义" style="display: none">')?>
    
    <div class="btn-group">
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    </div>
<?= Form::close('id') ?>
