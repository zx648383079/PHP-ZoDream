<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '个人资料';
?>

    <h1><?=$this->title?></h1>
    <form data-type="ajax" action="<?=$this->url('./account/update')?>" method="post" class="form-table" role="form">
        <div class="input-group">
            <label>用户名</label>
            <div>
                <input name="name" type="text" class="form-control"  placeholder="输入用户名" required value="<?=$model->name?>">
            </div>
        </div>
        <div class="input-group">
            <label>邮箱</label>
            <div>
                <input name="email" type="email" class="form-control"  placeholder="输入邮箱" required value="<?=$model->email?>">
            </div>
        </div>
        <div class="input-group">
            <label>性别</label>
            <div>
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
        </div>
        <div class="input-group">
            <label for="thumb">头像</label>
            <div class="file-input">
                <input type="text" id="thumb" name="avatar" placeholder="请输入图片" value="<?=$model->avatar?>">
                <button type="button" data-type="upload">上传</button>
                <button type="button" data-type="preview">预览</button>
            </div>
        </div>
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    </form>