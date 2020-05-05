<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerJsFile('@jquery.min.js')
    ->registerJsFile('@jquery.lazyload.min.js')
    ->registerJsFile('@book.min.js');
?>
    <div class="box-container ft" style="position:relative">
        <div class="center">
            <div class="bot_logo"><a href="/" title="<?=$this->site_name?>"><img src="/assets/images/wap_logo.png" alt="<?=$this->site_name?>" /></a></div>
            <div class="link">
            <div class="z"><?=$this->site_name?></div>
            <div class="f"><span>版权声明：<?=$this->site_name?></span></div>
            </div>
        </div>
    </div>
    <?php if(!app()->isDebug()):?>
        <div class="demo-tip"></div>
    <?php endif;?>
   <?=$this->footer()?>
   </body>
</html>