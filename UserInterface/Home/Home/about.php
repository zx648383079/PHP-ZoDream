<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '关于';
$this->set([
    'keywords' => '关于',
    'description' => '本站及其框架接受任何友好建议，欢迎任何友好朋友在此留下意见。'
]);
?>

<div class="container">
    <div class="about-box">
        <div class="about-top heading">
            <h1>关于本站</h1>
        </div>
        <div class="about-bottom">
            <h4>本站是基于 zodream 框架开发的展示站。</h4>
            <p>zodream, PHP 轻量级框架，主要代码来源为工作积累。本站及其框架保持永久更新。</p>
        </div>
    </div>

    <div class="contact-box">
        <div class="heading">
            <h2>意见反馈</h2>
            <p>本站及其框架接受任何友好建议，欢迎任何友好朋友在此留下意见。</p>
        </div>
        <div>
            <form method="post">
                <div class="col-md-6">
                    <input type="text" name="name" value="" placeholder="称呼" required="">
                    <input type="email" name="email" value="" placeholder="邮箱" required="">
                    <input type="text" name="phone" value="" placeholder="联系方式">
                </div>
                <div class="col-md-6">
                    <textarea name="content" placeholder="建议内容"></textarea>
                    <button type="submit" class="btn btn-show">发送</button>
                </div>
            </form>
            <div class="clearfix"> </div>
        </div>
    </div>
</div>