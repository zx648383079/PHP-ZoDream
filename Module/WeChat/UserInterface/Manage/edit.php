<?php
defined('APP_DIR') or exit();
use Module\WeChat\Domain\Model\WeChatModel;
/** @var $this \Zodream\Template\View */
$this->title = 'ZoDream';
$this->extend('layouts/header');
?>

<div class="page-tip">
    <p class="blue">操作提示</p>
    <ul>
        <li>添加微信公众号</li>
    </ul>
    <span class="toggle"></span>
</div>

<div>
    <form class="form-inline" data-type="ajax" action="<?=$this->url('./manage/save')?>" method="post">
        <div class="input-group">
            <label for="name">公众号名称</label>
            <input type="text" id="name" name="name" placeholder="请输入公众号名称" required value="<?=$model->name?>" size="100">
        </div>
        <div class="input-group">
            <label for="token">微信服务Token(令牌)</label>
            <input type="text" id="token" name="token" placeholder="请输入微信服务Token(令牌)" required value="<?=$model->token?>" size="100">
        </div>
        <div class="input-group">
            <label for="account">微信号</label>
            <input type="text" id="account" name="account" placeholder="请输入微信号" required value="<?=$model->account?>" size="100">
        </div>
        <div class="input-group">
            <label for="original">原始ID</label>
            <input type="text" id="original" name="original" placeholder="请输入原始ID" required value="<?=$model->original?>" size="100">
        </div>
        <div class="input-group">
            <label for="type">公众号类型</label>
            <select class="form-control" name="type">
                <?php foreach(WeChatModel::$types as $key => $item):?>
                    <option value="<?=$key?>" <?=$model->type == $item ? 'selected' : ''?>><?=$item?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="input-group">
            <label for="appid">AppID</label>
            <input type="text" id="appid" name="appid" placeholder="请输入AppID(应用ID)" required value="<?=$model->appid?>" size="100">
        </div>
        <div class="input-group">
            <label for="secret">AppSecret</label>
            <input type="text" id="secret" name="secret" placeholder="请输入AppSecret(应用密钥)" required value="<?=$model->secret?>" size="100">
        </div>
        <div class="input-group">
            <label for="aes_key">消息加密秘钥</label>
            <input type="text" id="aes_key" name="aes_key" placeholder="请输入消息加密秘钥EncodingAesKey" required value="<?=$model->aes_key?>" size="100">
        </div>
        <div class="input-group">
            <label for="avatar">头像</label>
            <div class="file-input">
                <input type="text" id="avatar" name="avatar" placeholder="请输入头像地址" required value="<?=$model->avatar?>" size="70">
                <button type="button">上传</button>
                <button type="button">预览</button>
            </div>
        </div>
        <div class="input-group">
            <label for="qrcode">二维码</label>
            <div class="file-input">
                <input type="text" id="qrcode" name="qrcode" placeholder="请输入二维码地址" required value="<?=$model->qrcode?>" size="70">
                <button type="button">上传</button>
                <button type="button">预览</button>
            </div>
        </div>
        <div class="input-group">
            <label for="address">所在地址</label>
            <input type="text" id="address" name="address" placeholder="请输入所在地址" required value="<?=$model->address?>" size="100">
        </div>
        <div class="input-group">
            <label for="description">公众号简介</label>
            <input type="text" id="description" name="description" placeholder="请输入公众号简介" required value="<?=$model->description?>" size="100">
        </div>
        <div class="input-group">
            <label for="username">账户</label>
            <input type="text" id="username" name="username" placeholder="请输入微信官网登录名(邮箱)" required value="<?=$model->username?>" size="100">
        </div>
        <div class="input-group">
            <label for="password">密码</label>
            <input type="text" id="password" name="password" placeholder="请输入微信官网登录密码" required value="<?=$model->password?>" size="100">
        </div>
        <button class="btn btn-primary">保存</button>
        <input type="hidden" name="id" value="<?=$model->id?>">
    </form>
</div>


<?php $this->extend('layouts/footer');?>