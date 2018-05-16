<?php
use Zodream\Template\View;
/** @var $this View */

$this->title = $model->id > 0 ? '编辑' : '新增'. '用户';

$this->extend('../layouts/header');
?>

    <h1><?=$this->title?></h1>
    <form data-type="ajax" action="<?=$this->url('./user/save')?>" method="post" class="form-table" role="form">
        <div class="input-group">
            <label>用户名</label>
            <input name="name" type="text" class="form-control"  placeholder="输入用户名" required value="<?=$model->name?>">
        </div>
        <div class="input-group">
            <label>邮箱</label>
            <input name="email" type="email" class="form-control"  placeholder="输入邮箱" required value="<?=$model->email?>">
        </div>
        <div class="input-group">
            <label>性别</label>
            <label>
                <input value="0" name="sex" type="radio" <?=$model->sex == 0 ? 'checked': ''?>> 未知
            </label>
            <label>
                <input value="1" name="sex" type="radio" <?=$model->sex == 1 ? 'checked': ''?>> 女
            </label>
            <label>
                <input value="2" name="sex" type="radio" <?=$model->sex == 2 ? 'checked': ''?>> 男
            </label>
        </div>
        <div class="input-group">
            <label for="thumb">头像</label>
            <div class="file-input">
                <input type="text" id="thumb" name="avatar" placeholder="请输入图片" value="<?=$model->avatar?>" size="70">
                <button type="button">上传</button>
                <button type="button">预览</button>
            </div>
        </div>
        <div class="input-group">
            <label>密码</label>
            <input name="password" type="password" class="form-control"  placeholder="输入密码">
        </div>
        <div class="input-group">
            <label>确认密码</label>
            <input name="confirm_password" type="password" class="form-control"  placeholder="确认密码">
        </div>
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
        <input type="hidden" name="id" value="<?=$model->id?>">
    </form>

<?php
$this->extend('../layouts/footer');
?>