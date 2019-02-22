<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$js = <<<JS
$('input[name=keywords]').focus();
JS;
$this->registerJs($js);
?>
<header class="top">
    <div class="search-box">
        <form action="<?=$this->url('./mobile/search')?>">
            <i class="fa fa-search" aria-hidden="true"></i>
            <input type="text" name="keywords" value="<?=$this->keywords?>" placeholder="搜索" autocomplete="off" onkeyup="$(this).next().toggle(!!$(this).val())">
            <i class="fa fa-times-circle hide" onclick="$(this).hide().prev().val('').focus()"></i>
        </form>
        <a class="cancel-btn" href="javascript:history.back();">取消</a>
    </div>
</header>