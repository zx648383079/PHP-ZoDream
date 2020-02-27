<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>

<div class="micro-publish">
    <div class="title">有什么有趣的代码想分享大家?
    </div>
    <form action="<?=$this->url('./create')?>" method="post">
        <div class="input">
            <textarea name="content" required></textarea>
        </div>
        <div class="actions">
            <div class="lang-input">
                语言：<input type="text" name="language" size="20">
            </div>
            <div class="tag-input">
                标签：<input type="text" name="tags">
            </div>
            <button>发布</button>
        </div>
    </form>
</div>
