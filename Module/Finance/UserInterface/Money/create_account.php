<?php
use Zodream\Template\View;
/** @var $this View */

$this->title = $model->id > 0 ? '编辑' : '新增'. '资金账户';

$this->extend('layouts/header');
?>

    <h1><?=$this->title?></h1>
    <form data-type="ajax" action="<?=$this->url('./money/save_account')?>" method="post" class="form-table" role="form">
        <div class="input-group">
            <label>账户名称</label>
            <input name="name" type="text" class="form-control"  placeholder="输入形态名称" value="<?=$model->name?>">
        </div>
        <div class="input-group">
            <label>可用金额</label>
            <input name="money" type="text" class="form-control" placeholder="可用金额" value="<?=$model->money?>">
        </div>
        <div class="input-group">
            <label>冻结金额</label>
            <input name="frozen_money" type="text" class="form-control" placeholder="冻结金额" value="<?=$model->frozen_money?>">
        </div>
        <div class="input-group">
            <label>说明</label>
            <textarea name="remark" class="form-control" placeholder="备注信息"><?=$model->remark?></textarea>
        </div>
        <div class="input-group">
            <label>
                <input value="1" name="status" type="checkbox" <?=$model->status || $model->id < 1 ? 'checked': ''?>> 是否启用
            </label>
        </div>
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
        <input type="hidden" name="id" value="<?=$model->id?>">
    </form>

<?php
$this->extend('layouts/footer');
?>