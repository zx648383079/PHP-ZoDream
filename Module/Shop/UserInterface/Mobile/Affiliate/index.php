<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '我的分享';

$header_btn = <<<HTML
<a class="right" href="#">
    <i class="fa fa-share"></i>
</a>
HTML;
$this->extend('../layouts/header', compact('header_btn'));
?>

<div class="has-header">
    <div class="share-box">
        <img src="<?=$this->url('./mobile/affiliate/qr')?>" alt="">
    </div>
</div>
