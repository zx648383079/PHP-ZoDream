<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '验证分享密码';
?>

<div class="password-box">
    <div class="box-header">
        <div class="avatar">
            <img src="<?=$user->avatar?>" alt="">
        </div>
        <div class="name">
            <strong><?=$user->name?></strong>
            给您加密分享了文件
        </div>
    </div>
    <form class="box-body" action="<?=$this->url()?>" method="post">
        <p>请输入提取码：</p>
        <input type="text" name="password" placeholder="请输入提取码">
        <button>确定</button>
    </form>
</div>