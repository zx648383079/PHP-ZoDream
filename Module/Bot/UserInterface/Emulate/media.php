<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\Bot\Domain\Model\MenuModel;
/** @var $this View */
$this->title = $model->title;
$js = <<<JS
bindEmulateMedia();
JS;
$this->registerJsFile('@jquery.lazyload.min.js')->registerJs($js);
?>
<div class="rich_media">
    <h2 class="rich_media_title"><?=$model->title?></h2>
    <div id="meta_content" class="rich_media_meta_list">
        <span class="rich_media_meta rich_media_meta_nickname" id="profileBt">
            <a href="javascript:void(0);" id="js_name">
                <?=$wx->name?></a>
            <div id="js_profile_qrcode" class="profile_container" style="display:none;">
                <div class="profile_inner">
                    <strong class="profile_nickname"><?=$wx->name?></strong>
                    <img class="profile_avatar" id="js_profile_qrcode_img" src="" alt="">

                    <p class="profile_meta">
                        <label class="profile_meta_label">微信号</label>
                        <span class="profile_meta_value"><?=$wx->account?></span>
                    </p>

                    <p class="profile_meta">
                        <label class="profile_meta_label">功能介绍</label>
                        <span
                            class="profile_meta_value"><?=$wx->description?></span>
                    </p>

                </div>
                <span class="profile_arrow_wrp" id="js_profile_arrow_wrp">
                    <i class="profile_arrow arrow_out"></i>
                    <i class="profile_arrow arrow_in"></i>
                </span>
            </div>
        </span>
        <em id="publish_time" class="rich_media_meta rich_media_meta_text"><?=$this->ago($model->getAttributeSource('created_at'))?></em>
    </div>
    <div class="rich_media_content">
        <?=$model->content?>
    </div>
</div>