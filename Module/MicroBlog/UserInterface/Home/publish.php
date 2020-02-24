<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>

<div class="micro-publish">
    <div class="title">有什么新鲜事想告诉大家?
        <div class="tip">
            已输入<em>0</em>字
        </div>
    </div>
    <form action="<?=$this->url('./create')?>" method="post">
        <div class="input">
            <textarea name="content" required></textarea>
        </div>
        <div class="actions">
            <div class="tools">
                <i class="fa fa-smile"></i>
                <i class="fa fa-image"></i>
                <i class="fa fa-video"></i>
                <i class="fa fa-music"></i>
            </div>
            <select>
                <option value="">公开</option>
            </select>
            <button>发布</button>
        </div>
    </form>
</div>

<div class="dialog-emoji">
    <div class="dialog-header">
        <ul class="tab-header">
            <li class="active">默认</li>
        </ul>
        <i class="fa fa-times"></i>
    </div>
    <div class="dialog-body">
        <div class="tab-item active">
            <div class="emoji-item" title="哼">
                <img src="//img.t.sinajs.cn/t4/appstyle/expression/ext/normal/e3/2018new_weixioa02_org.png" alt="">
            </div>
        </div>
    </div>
</div>

<div class="dialog-upload">
    <div class="dialog-header">
        <span>请选择上传的文件</span>
        <i class="fa fa-times"></i>
    </div>
    <div class="dialog-body">
        
        <div class="add-btn">
            <i class="fa fa-plus"></i>
        </div>
    </div>
</div>