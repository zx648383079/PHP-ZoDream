<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = $model->id > 0 ? '编辑' : '新增'.'配置项目';

$js = <<<JS
    var start_at = $('[name=start_at]').datetimer();
    $('[name=end_at]').datetimer({
        min: start_at
    });
JS;


$this->registerJs($js, View::JQUERY_READY);
?>

    <h1><?=$this->title?></h1>
    <form data-type="ajax" action="<?=$this->url('./money/save_project')?>" method="post" class="form-table" role="form">
        <div class="input-group">
            <label>配置名称</label>
            <input name="name" type="text" class="form-control"  placeholder="输入配置名称" required value="<?=$model->name?>">
        </div>
        <div class="input-group">
            <label>别名</label>
            <input name="alias" type="text" class="form-control"  placeholder="输入配置名称" value="<?=$model->alias?>">
        </div>
        <div class="input-group">
            <label>资金</label>
            <input name="money" type="text" class="form-control" placeholder="输入配置数目" required value="<?=$model->money?>">
        </div>
        <div class="input-group">
            <label>资金账户</label>
            <select class="form-control" name="account_id">
                <?php foreach($account_list as $item):?>
                    <option value="<?=$item->id;?>" <?=$model->id == $model->account_id ? 'selected' : ''?>><?=$item->name?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="input-group">
            <label>理财产品</label>
            <select class="form-control" name="product_id">
                <?php foreach($product_list as $item):?>
                    <option value="<?=$item->id;?>" <?=$model->id == $model->product_id ? 'selected' : ''?>><?=$item->name?></option>
                <?php endforeach;?>
            </select>
        </div>
        <!--    <div class="input-group">-->
        <!--        <label>资金占比</label>-->
        <!--        <input name="accounted_for" type="text" class="form-control" placeholder="输入资金占比" value="">-->
        <!--    </div>-->
        <div class="input-group">
            <label>(预估)收益率</label>
            <input name="earnings" type="text" class="form-control" placeholder="输入收益率" value="<?=$model->earnings?>">
        </div>
        <div class="input-group">
            <label>开始时间</label>
            <input name="start_at" type="text" class="form-control" placeholder="选择开始时间" value="<?=$model->start_at?>">
        </div>
        <div class="input-group">
            <label>结束时间</label>
            <input name="end_at" type="text" class="form-control" placeholder="选择结束时间" value="<?=$model->end_at?>">
        </div>
        <div class="input-group">
            <label>备注</label>
            <textarea name="remark" class="form-control" placeholder="备注信息"><?=$model->remark?></textarea>
        </div>
        <!--  <div class="checkbox">-->
        <!--    <label>-->
        <!--      <input value="1" name="status" type="checkbox" checked > 是否启用-->
        <!--    </label>-->
        <!--  </div>-->
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
        <input type="hidden" name="id" value="<?=$model->id?>">
    </form>