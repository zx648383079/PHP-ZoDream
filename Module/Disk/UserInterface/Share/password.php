<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '验证分享密码';
?>

<div class="password-box">
    <form action="" method="post">
        <input type="text" name="password" placeholder="请输入密码">
        <button>确定</button>
    </form>
</div>