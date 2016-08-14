<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Html;
use Zodream\Domain\Access\Auth;
use Zodream\Domain\Html\Bootstrap\FormWidget;
use Zodream\Infrastructure\Url\Url;
/** @var $this \Zodream\Domain\View\View */
/** @var $page \Zodream\Domain\Html\Page */

$this->registerJs('require(["admin/account"]);');
$this->registerCssFile('cropper.min.css');
$this->registerCssFile('sitelogo.css');
$this->registerCssFile('zodream/account.css');
$this->extend([
    'layout/head',
    'layout/navbar'
]);
?>

<div class="container">

    <div class="row">
        <div class="col-md-3">
            <ul class="list-group">
                <li class="list-group-item">
                    <?=Html::a('个人信息', 'info')?>
                </li>
                <li class="list-group-item">
                    <?=Html::a('安全中心', ['security'])?>
                </li>
                <li class="list-group-item">
                    <?=Html::a('隐私设置', ['setting'])?>
                </li>
            </ul>
        </div>
        <div id="crop-avatar" class="col-md-9">
            <?=
            FormWidget::begin(Auth::user()->get())
                ->html('avatar', function ($avatar) {
                    return <<<HTML
<div class="col-md-offset-2 avatar-view">
<img src="{$avatar}" alt="头像" class="img-rounded">
<input type="hidden" name="avatar" value="{$avatar}">                    
</div>

HTML;

                })
                ->text('name', ['label' => '昵称'])
                ->email('email', ['label' => '邮箱'])
                ->radio('sex', ['label' => '性别', 'value' => '男'])
                ->radio('sex', ['label' => '性别', 'value' => '女'])
                ->button()
                ->end()
            ?>
        </div>
    </div>
</div>

<div class="modal fade" id="avatar-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="avatar-form" action="<?=Url::to(['avatar'])?>" enctype="multipart/form-data" method="post">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal" type="button">×</button>
                    <h4 class="modal-title" id="avatar-modal-label">更改头像</h4>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#choose" aria-controls="choose" role="tab" data-toggle="tab">选择</a></li>
                        <li role="presentation"><a href="#upload" aria-controls="upload" role="tab" data-toggle="tab">上传</a></li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="choose">
                            <div>
                                <ul>
                                    <?php for ($i = 0; $i < 49; $i ++) :?>
                                    <li>
                                        <img src="/assets/images/avatar/<?=$i?>.png">
                                    </li>
                                    <?php endfor;?>
                                </ul>
                            </div>
                            <button class="btn btn-primary">确定</button>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="upload">
                            <div class="avatar-body">
                                <div class="avatar-upload">
                                    <input class="avatar-src" name="avatar_src" type="hidden">
                                    <input class="avatar-data" name="avatar_data" type="hidden">
                                    <label for="avatarInput">图片上传</label>
                                    <input class="avatar-input" id="avatarInput" name="avatar_file" type="file"></div>
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="avatar-wrapper"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="avatar-preview preview-lg"><img src="./Document_files/logo.jpg"></div>
                                        <div class="avatar-preview preview-md"><img src="./Document_files/logo.jpg"></div>
                                        <div class="avatar-preview preview-sm"><img src="./Document_files/logo.jpg"></div>
                                    </div>
                                </div>
                                <div class="row avatar-btns">
                                    <div class="col-md-9">
                                        <div class="btn-group">
                                            <button class="btn" data-method="rotate" data-option="-90" type="button" title="Rotate -90 degrees"><i class="fa fa-undo"></i> 向左旋转</button>
                                        </div>
                                        <div class="btn-group">
                                            <button class="btn" data-method="rotate" data-option="90" type="button" title="Rotate 90 degrees"><i class="fa fa-repeat"></i> 向右旋转</button>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-success btn-block avatar-save" type="submit"><i class="fa fa-save"></i> 保存修改</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="loading" aria-label="Loading" role="img" tabindex="-1"></div>



<?php $this->extend('layout/foot')?>
