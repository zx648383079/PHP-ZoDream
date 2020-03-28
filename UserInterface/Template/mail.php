<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->layout = false;
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>密码重置邮件-<?=__('site title')?></title>
</head>
<body>
<div>
    <h3>尊敬的<?=$name?>:</h3>
         您好！您于<?=$time?>在本站(<a href="https://zodream.cn" target="_blank" title="ZoDream">zodream.cn</a>)执行了密码重置操作，
    <?php if(isset($code) && strlen($code) < 10):?>
    请使用以下安全代码。
    <p>
    安全代码：<span style="font-weight: bold;"><?=$code?></span>
    </p>
    <?php else:?>
    请点击以下链接进行重置
    <p><a href="<?=$url?>" target="_blank" title="重置密码"><?=$url?></a></p>
    <?php endif;?>
         
    如果是您误收，请忽略！

    <p>请勿直接回复本邮件。 <b>（本邮件有效为30分钟）</b></p>
    <p style="text-align: center"><a href="https://zodream.cn" target="_blank" title="ZoDream">zodream.cn</a></p>
</div>
</body>
</html>