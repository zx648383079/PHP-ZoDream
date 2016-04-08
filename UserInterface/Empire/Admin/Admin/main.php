<?php
defined('APP_DIR') or exit();
$this->extend(array(
    'layout' => array(
        'head'
    ))
);
?>

<h1 class="text-center">欢迎使用 ZoDream CMS </h1>
<div class="panel">
    <h3 class="head">我的状态</h3>
    <div>
        登录者:  <?php $this->ech('name');?>   ,所属用户组:  超级管理员
        这是您第 <?php $this->ech('num');?> 次登录，上次登录时间： <?php $this->time($this->get('date'));?> ，登录IP： <?php $this->ech('ip');?>
    </div>
</div>

<?php
$this->extend(array(
    'layout' => array(
        'foot'
    ))
);
?>
