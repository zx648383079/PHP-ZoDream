<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\View\Engine\DreamEngine*/
/** @var $model \Domain\Model\FeedbackModel */
$this->extend(array(
    'layout' => array(
        'head',
        'navbar'
    )), array(
        'zodream/home.css'
    )
);
$model = $this->gain('model');
?>

    <div class="about">
        <div class="container">
            <div class="about-top heading">
                <h1>关于本站</h1>
            </div>
            <div class="about-bottom">
                <h4>本站是基于 ZoDream 框架开发的展示站，所有附属产品都属于 ZoDream 的繁衍物。</h4>
                <p>ZoDream, PHP 轻量级框架，主要代码来源为工作积累，例如：邮件发送基于 PHPMailer ；Bootstrap 基于Bootstrap, 思路来源 YII 。本站及其框架保持永久更新。</p>
            </div>
        </div>
    </div>

    <div class="contact">
        <div class="container">
            <div class="row heading">
                <h2>意见反馈</h2>
                <p>本站及其框架接受任何友好建议，欢迎任何友好朋友在此留下意见。</p>
            </div>
            <div class="row">
                <form method="post">
                    <div class="col-md-6">
                        <input type="text" name="name" value="<?=$model->name?>" placeholder="称呼" required="">
                        <input type="email" name="email" value="<?=$model->email?>" placeholder="邮箱" required="">
                        <input type="text" name="phone" value="<?=$model->phone?>" placeholder="联系方式">
                        <p class="text-danger"><?=empty($model->getError()) ? null: '输入有误！'?></p>
                    </div>
                    <div class="col-md-6">
                        <textarea name="content" placeholder="建议内容"><?=$model->content?></textarea>
                        <button type="submit" class="btn btn-show">发送</button>
                    </div>
                </form>
                <div class="clearfix"> </div>
            </div>
        </div>
    </div>


<?php
$this->extend(array(
    'layout' => array(
        'foot'
    ))
);
?>