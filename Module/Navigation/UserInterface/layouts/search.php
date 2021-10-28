<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$js = <<<JS
bindSearch();
JS;
$this->registerJs($js);
?>
<div class="search-box">
    <form class="search-input" action="<?=$this->url('./search')?>">
        <button class="search-btn" type="submit">
            <i class="fa fa-search"></i>
        </button>
        <input type="text" name="keywords" value="<?=$this->text($this->get('keywords'))?>" placeholder="请输入关键字，按回车 / Enter 搜索" autocomplete="off">
        <div class="clear-btn">
            <i class="fa fa-close"></i>
        </div>
    </form>
    <div class="suggest-body">
        <ul>
            
        </ul>
    </div>
</div>