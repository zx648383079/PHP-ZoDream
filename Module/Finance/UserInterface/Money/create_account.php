<?php
use Zodream\Template\View;
/** @var $this View */

$this->title = '新增资金账户';

$this->extend('layouts/header');
?>

    <h1>新增资金账户</h1>
    <form data-type="ajax" action="<?=$this->url('./money/save_account')?>" method="post" class="form-table" role="form">
        <div class="input-group">
            <label>账户名称</label>
            <input name="name" type="text" class="form-control"  placeholder="输入形态名称" value="">
        </div>
        <div class="input-group">
            <label>可用金额</label>
            <input name="money" type="text" class="form-control" placeholder="可用金额" value="">
        </div>
        <div class="input-group">
            <label>冻结金额</label>
            <input name="frozen_money" type="text" class="form-control" placeholder="冻结金额" value="">
        </div>
        <div class="input-group">
            <label>说明</label>
            <input name="remark" type="text" class="form-control" placeholder="备注信息" value="">
        </div>
        <div class="input-group">
            <label>
                <input value="1" name="status" type="checkbox" checked > 是否启用
            </label>
        </div>
        <button type="submit" class="btn btn-success">确认新增</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    </form>

<?php
$this->extend('layouts/footer');
?>