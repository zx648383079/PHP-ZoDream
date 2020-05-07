<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = __('本站协议及隐私政策');
$this->set([
    'keywords' => '本站协议及隐私政策',
    'description' => '本站协议及隐私政策'
]);
?>
<div class="container">
    <div style="line-height:25px; padding-bottom: 30px; padding-top: 30px">
        <h1 style="font-size: 30px; text-align: center; height: 50px;">本站协议</h1>
        <h2 class="h2">协议细则</h2>
        <h2 class="h2">1、本网站服务条款的确认和接纳</h2>
        <p class="p1">本网站各项服务的所有权和运作权归本网站拥有。</p>
        <h2 class="h2">2、用户发布的内容</h2>
        <p class="p1">由用户发布的内容所有权归用户，但请注意非原创或违法内容本站有权删除。</p>
    </div>

    <div style="line-height:25px; padding-bottom: 30px; padding-top: 30px">
        <h1 style="font-size: 30px; text-align: center; height: 50px;">隐私政策</h1>
        <h2 class="h2">1、本站如何收集您的个人信息</h2>
        <p class="p1">注册用户过程中将会收集您的个人信息，如：电子邮件地址。为了保护个人隐私，您不应提供除特别要求之外的任何其它信息。</p>
        <h2 class="h2">2、本站如何使用您的个人信息</h2>
        <p class="p1">通过您的个人信息实现密码找回功能。</p>
        <h2 class="h2">3、个人信息安全</h2>
        <p class="p1">本应用将通过对用户密码进行加密等安全措施确保您的信息不丢失，不被滥用和变造。</p>
        <h2 class="h2">4、本站会将个人信息保存多久</h2>
        <p class="p1">本应用永久保存个人信息直至用户主动注销。</p>
    </div>
</div>