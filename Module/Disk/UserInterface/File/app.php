<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>

<div class="app-box">
    <div class="app-icon">
        <img src="https://pp.myapp.com/ma_icon/0/icon_6633_1540954632/96">
    </div>
    <div class="app-name">
        QQ
    </div>
    <a class="app-url" href="">直接下载</a>
    <div class="app-qr">
        <img src="<?=url('./file/qr', ['id' => $disk['id']])?>">
    </div>
</div>