<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = __('本站服务协议及隐私政策');
$this->set([
    'keywords' => '本站服务协议及隐私政策',
    'description' => '本站服务协议及隐私政策'
]);
?>
<div class="container">
    <div class="agreement-box">
        <div class="title" id="top">本站服务协议</div>

        <div class="row">
            <div class="col-md-12">
            本协议条款（以下简称“协议”）将约束
            <a href="#serviceslist">此处</a>
            （#serviceslist）在条款末尾列出的产品、网站和服务。
            您创建帐户、使用服务或者在收到了变更通知后继续使用服务，即表示您接受本协议。
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <ul class="nav-list">
                    <li>
                        <a href="#privacy">您的隐私</a>
                    </li>
                    <li>
                        <a href="#content">您的内容</a>
                    </li>
                    <li>
                        <a href="#codeOfConduct">行为准则</a>
                    </li>
                    <li>
                        <a href="#limitationOfLiability">责任限制</a>
                    </li>
                    <li>
                        <a href="#serviceslist">涵盖的服务</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-9">
                <div id="privacy" class="nav-panel">
                    <div class="nav-header">
                    您的隐私
                    </div>
                    <div class="nav-desc">
                        <p>1、<strong>本站如何收集您的个人信息</strong> </p>
                        <p class="p1">注册用户过程中将会收集您的个人信息，如：电子邮件地址。为了保护个人隐私，您不应提供除特别要求之外的任何其它信息。</p>
                        <p>2、<strong>本站如何使用您的个人信息</strong></p>
                        <p class="p1">通过您的个人信息实现密码找回功能。</p>
                        <p>3、<strong>个人信息安全</strong></p>
                        <p class="p1">本应用将通过对用户密码进行加密等安全措施确保您的信息不丢失，不被滥用和变造。</p>
                        <p>4、<strong>本站会将个人信息保存多久</strong></p>
                        <p class="p1">本应用永久保存个人信息直至用户主动注销。</p>
                    </div>
                </div>
                <div id="content" class="nav-panel">
                    <div class="nav-header">
                    您的内容
                    </div>
                    <div class="nav-desc">
                        <p class="p1">我们的许多服务允许您存储或共享您的内容，或者接收他人发送的材料。我们不会对您的内容主张所有权。您的内容仍归您所有，您自行对您的内容负责。</p>
                        <p class="p1">由用户发布的内容所有权归用户，但请注意非原创或违法内容本站有权删除。</p>
                    </div>
                </div>
                <div id="codeOfConduct" class="nav-panel">
                    <div class="nav-header">
                    行为准则
                    </div>
                    <div class="nav-desc">
                        <p>1. 不得从事任何非法活动</p>
                        <p>2. 不得从事任何利用、危害或可能危害儿童的活动。</p>
                        <p>3. 不得侵犯他人的权利</p>
                        <p>4. 不得从事侵犯他人隐私或数据保护权利的活动。</p>
                        <p>5. 如果您违反本协议，我们可能会停止为您提供服务或关闭您的帐户。</p>
                    </div>
                </div>
                <div id="limitationOfLiability" class="nav-panel">
                    <div class="nav-header">
                    责任限制
                    </div>
                    <div class="nav-desc">
                        <p>1. 对于因超出本站合理控制范围的情况（例如，劳资纠纷、不可抗力、战争或恐怖主义行为、恶意破坏、意外事故或遵守任何适用法律或政府命令）而导致本站无法履行或延迟履行其义务，本站对此不承担任何责任或义务。本站将尽最大努力降低这些事件的影响，并履行未受影响的义务。</p>
                    </div>
                </div>
                <div id="serviceslist" class="nav-panel">
                    <div class="nav-header">
                    涵盖的服务
                    </div>
                    <div class="nav-desc">
                        <p>服务协议涵盖以下产品、应用和服务</p>
                        <ul>
                            <li>zodream.cn</li>
                            <li>Regex Generator</li>
                            <li>聚百客综合商店</li>
                            <li>我的时间回忆薄</li>
                        </ul>
                    </div>
                </div>

                <a class="goto-top" href="#top" title="返回页首">
                    <i class="fa fa-arrow-up"></i>
                    返回页首
                </a>
            </div>
        </div>

    </div>
</div>