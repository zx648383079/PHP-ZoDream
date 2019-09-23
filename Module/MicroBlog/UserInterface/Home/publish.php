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