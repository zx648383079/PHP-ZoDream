<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerJsFile('@jquery.min.js');
?>
    <div class="Layout ft" style="position:relative">
    <div class="center">
        <div class="bot_logo"><a href="/" title="<?=$this->site_name?>"><img src="/images/bot_logo.png" alt="<?=$this->site_name?>" /></a></div>
        <div class="link">
        <div class="z"><?=$this->site_name?></div>
        <div class="f"><span>版权声明：<?=$this->site_name?></span></div>
        </div>
    </div>
    </div>
    <div class="floatBox">
    <ul class="fbox">
        <li class="fli sq"><a href="#" class="fbtn" title="收藏本书">收藏本书</a></li>
        <li class="fli udLI"><a class="fbtn UD">返回顶部</a></li>
    </ul>
    </div>
    <div class="TIP"></div>
    <div class="MAK"></div>
   <?=$this->footer()?>
   </body>
</html>