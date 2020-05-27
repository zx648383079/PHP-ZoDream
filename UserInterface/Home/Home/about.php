<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Form;
/** @var $this View */

$this->title = __('About Us');
$this->set([
    'keywords' => __('About Us'),
    'description' => '本站及其框架接受任何友好建议，欢迎任何友好朋友在此留下意见。'
]);
?>

<div class="container">
    <div class="row about-header">
        <div class="col-md-4">
            <?=$this->node('author')?>
        </div>
        <div class="col-md-8">
            <div class="about-box">
                <div class="about-top heading">
                    <h1><?=__('About Us')?></h1>
                </div>
                <div class="about-bottom">
                    <h4>本站是基于 zodream 框架开发的展示站。</h4>
                    <p>zodream, PHP 轻量级框架，主要代码来源为工作积累。本站及其框架保持永久更新。</p>
                    <p>本站除博客外，其他栏目均属于代码测试项目，只为测试代码之用，不会产生任何商业用途，特别说明，本站内的任何功能，只用于本站内部测试。</p>
                    <p>本站建设意图为收藏有价值的事物，如有侵权，请提交反馈。</p>
                    <p>本站所有内容禁止作为商业收费内容转载。</p>
                    <div class="autograph">2020-05-26 留</div>
                </div>
            </div>
        </div>
    </div>

    <div class="contact-box">
        <div class="heading">
            <h2><?=__('Feedback')?></h2>
            <p>本站及其框架接受任何友好建议，欢迎任何友好朋友在此留下意见。</p>
        </div>
        <div>
            <?= Form::open('/contact/home/feedback', 'POST', ['data-type' => 'ajax',]) ?>
                <div class="col-md-6">
                    <input type="text" name="name" value="" placeholder="<?=__('Nick Name')?>" required="">
                    <input type="email" name="email" value="" placeholder="<?=__('Email')?>" required="">
                    <input type="text" name="phone" value="" placeholder="<?=__('Contact')?>">
                </div>
                <div class="col-md-6">
                    <textarea name="content" placeholder="<?=__('Feedback Content')?>"></textarea>
                    <button type="submit" class="btn btn-show"><?=__('Send')?></button>
                </div>
            <?= Form::close() ?>
            <div class="clearfix"> </div>
        </div>
    </div>
</div>