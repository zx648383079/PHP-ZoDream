<?php
use Zodream\Template\View;
/** @var $this View */

$this->title = $model->id > 0 ? '编辑' : '新增'.'生活预算';


$this->extend('layouts/header');
?>

    <h1><?=$this->title?></h1>
    <form data-type="ajax" action="<?=$this->url('./budget/save')?>" method="post" class="form-table" role="form">
        <div class="input-group">
            <label>名称</label>
            <input name="name" type="text" class="form-control" size="16" value="" placeholder="请输入名称" />
        </div>
        <div class="input-group">
            <label>预算(元)</label>
            <input name="budget" type="text" class="form-control" value="1000" />
        </div>
        <div class="input-group">
            <label>周期</label>
            <select class="form-control" name="cycle">
                <?php foreach(['一次', '每天', '每周', '每月', '每年'] as $key => $item):?>
                    <option value="<?=$key?>" <?=$key == $model->cycle ? 'selected' : ''?>><?=$item?></option>
                <?php endforeach;?>
            </select>
        </div>
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
        <input type="hidden" name="id" value="<?=$model->id?>">
    </form>

<?php
$this->extend('layouts/footer');
?>