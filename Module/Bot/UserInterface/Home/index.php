<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '微信公众号';
?>
<div class="qr-box">
    <img src="<?=$this->asset('images/wx.jpg')?>" alt="自在test">
</div>