<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream Chat';
$js = <<<JS
registerChat()
JS;
$this->registerJs($js);
?>

<button id="toggle-btn">切换悬浮/固定</button>

<div class="dialog-chat dialog-fixed">
    <!-- 会员列表 -->
    <div class="dialog-box dialog-chat-box dialog-min" style="display: block">
        <div class="dialog-header">
            <div class="dialog-action">
                <i class="fa fa-plus"></i>
                <i class="fa fa-minus"></i>
                <i class="fa fa-close"></i>
            </div>
        </div>
        <div class="dialog-info">
            <div class="dialog-info-avatar">
                <img src="<?=$user->avatar?>" alt="">
            </div>
            <div class="dialog-info-name">
                <h3><?=$user->name?></h3>
                <p>......</p>
            </div>
            <div class="dialog-message-count">
                99
            </div>
        </div>
        <div class="dialog-tab">
            <div class="dialog-tab-header">
                <div class="dialog-tab-item active">
                    <i class="fa fa-comment"></i>
                </div><div class="dialog-tab-item">
                    <i class="fa fa-user"></i>
                </div><div class="dialog-tab-item">
                    <i class="fa fa-comments"></i>
                </div>
            </div>
            <div class="dialog-tab-box">
                <div class="dialog-tab-item active">
                </div>
                <div class="dialog-tab-item">
                </div>
                <div class="dialog-tab-item">
                </div>
            </div>
        </div>
        <div class="dialog-menu">
            <ul>
                <li>
                    <i class="fa fa-eye"></i>
                    查看资料</li>
                <li>
                    <i class="fa fa-bookmark"></i>
                    移动好友</li>
                <li>
                    <i class="fa fa-trash"></i>
                    删除好友</li>
            </ul>
        </div>
    </div>
    <!-- 聊天室 -->
    <div class="dialog-box dialog-chat-room">
        <div class="dialog-header">
            <div class="dialog-title">与 xx 聊天中</div>
            <div class="dialog-action">
                <i class="fa fa-minus"></i>
                <i class="fa fa-close"></i>
            </div>
        </div>
        <div class="dialog-message-box">
            
        </div>
        <div class="dialog-message-tools">
            <i class="fa fa-smile"></i>
            <i class="fa fa-image"></i>
            <i class="fa fa-camera"></i>
            <i class="fa fa-video"></i>
            <i class="fa fa-file"></i>
            <i class="fa fa-gift"></i>
        </div>
        <div class="dialog-message-editor">
            <div class="dialog-message-text" contenteditable="true">

            </div>
            <div class="dailog-message-action">
                <button>发送</button>
            </div>
        </div>
    </div>
    <!-- 查找添加用户 -->
    <div class="dialog-box dialog-search-box">
        <div class="dialog-header">
            <div class="dialog-title dialog-tab-header">
                <div class="dialog-tab-item active">找人</div>
                <div class="dialog-tab-item">找群</div>
            </div>
            <div class="dialog-action">
                <i class="fa fa-close"></i>
            </div>
        </div>
        <div class="dialog-search">
            <input type="text">
            <i class="fa fa-search"></i>
        </div>
        <div class="dialog-search-list">
        </div>
    </div>
    <!-- 查看会员信息 -->
    <div class="dialog-box dialog-user-box">
        <div class="dialog-header">
            <div class="dialog-action">
                <i class="fa fa-close"></i>
            </div>
        </div>
        <div class="dialog-user-avatar">
            <img src="./image/avatar.jpg" alt="">
        </div>
        <h3 class="user-name">1231</h3>
        <div class="dialog-user-info">
            <p class="user-brief">123123</p>
            <p>123123</p>
            <p>123123</p>
        </div>
    </div>
    <!-- 申请好友 -->
    <div class="dialog-box dialog-apply-box">
        <div class="dialog-header">
            <div class="dialog-action">
                <i class="fa fa-close"></i>
            </div>
        </div>
        <div class="dialog-user-avatar">
            <img src="./image/avatar.jpg" alt="">
        </div>
        <h3 class="user-name">1231</h3>
        <div class="dialog-add-action">
            <textarea name="" placeholder="我是123123"></textarea>
            <select name="" id="">
                <option value="">选择分组</option>
            </select>
            <button class="dialog-yes">申请</button>
        </div>
    </div>
    <!-- 添加为好友 -->
    <div class="dialog-box dialog-add-box">
        <div class="dialog-header">
            <div class="dialog-action">
                <i class="fa fa-close"></i>
            </div>
        </div>
        <div class="dialog-user-avatar">
            <img src="./image/avatar.jpg" alt="">
        </div>
        <h3 class="user-name">1231</h3>
        <p class="user-brief">留言</p>
        <div class="dialog-add-action">
            <select name="" id="">
                <option value="">选择分组</option>
            </select>
            <button class="dialog-yes">同意</button>
            <button>拒绝</button>
        </div>
    </div>
    <div class="dialog-box dialog-apply-log-box">
        <div class="dialog-header">
            <div class="dialog-title">验证消息</div>
            <div class="dialog-action">
                <i class="fa fa-close"></i>
            </div>
        </div>
        <div class="dialog-apply-list">
            <div class="dialog-info" data-id="2">
                <div class="dialog-info-avatar">
                    <img src="http://zodream.localhost/assets/images/avatar/18.png" alt="">
                </div>
                <div class="dialog-info-name">
                    <h3>1606282309</h3>
                    <p>附加消息：123</p>
                </div>
                <div class="dialog-action">
                    <button>同意</button>
                    <button>忽略</button>
                </div>
            </div>
        </div>
    </div>
</div>
