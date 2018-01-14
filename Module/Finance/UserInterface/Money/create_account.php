<?php
use Zodream\Template\View;
/** @var $this View */

$this->title = '配置项目';

$this->extend('layouts/header');
?>

    <h1>新增配置项目</h1>
    <form data-type="ajax" action="<?=$this->url('./money/save_account')?>" method="post" class="form-table" role="form">
        <div class="input-group">
            <label>配置名称</label>
            <input name="asset_name" type="text" class="form-control"  placeholder="输入配置名称" value="">
        </div>
        <div class="input-group">
            <label>资金</label>
            <input name="number" type="text" class="form-control" placeholder="输入配置数目" value="">
        </div>
        <div class="input-group">
            <label>资金形态</label>
            <select class="form-control" name="money_form_id">
                <?php foreach($form_list as $item):?>
                    <option value="<?=$item->id;?>"><?=$item->name?></option>
                <?php endforeach;?>
            </select>
        </div>
        <!--    <div class="input-group">-->
        <!--        <label>资金占比</label>-->
        <!--        <input name="accounted_for" type="text" class="form-control" placeholder="输入资金占比" value="">-->
        <!--    </div>-->
        <div class="input-group">
            <label>(预估)收益率</label>
            <input name="earnings" type="text" class="form-control" placeholder="输入收益率" value="">
        </div>
        <div class="input-group">
            <label>开始时间</label>
            <input name="start_at" type="text" class="form-control" placeholder="输入收益率" value="">
        </div>
        <div class="input-group">
            <label>结束时间</label>
            <input name="end_at" type="text" class="form-control" placeholder="输入收益率" value="">
        </div>
        <div class="input-group">
            <label>备注</label>
            <input name="remark" type="text" class="form-control" placeholder="备注信息" value="">
        </div>
        <!--  <div class="checkbox">-->
        <!--    <label>-->
        <!--      <input value="1" name="status" type="checkbox" checked > 是否启用-->
        <!--    </label>-->
        <!--  </div>-->
        <button type="submit" class="btn btn-success">确认新增</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    </form>

<?php
$this->extend('layouts/footer');
?>