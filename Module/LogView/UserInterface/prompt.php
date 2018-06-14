<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream Log Viewer';
$url = $this->url($url);
$js = <<<JS
  var cd = $('#countdown'), 
    timer = parseInt(cd.text());
  window.setInterval(function(){
    if(timer <= 0){window.clearInterval();return;}
    timer --;
    cd.text(timer);
    if(timer == 0){
      window.location.href = "{$url}";
    }
  }, 1000); 
JS;

$this->registerJs($js, View::JQUERY_READY);
?>


<div class="wrapper" id="wrapper">
    <!-- header start -->
    <div class="header"><h2>系统提示</h2></div>
    <!-- header end -->
    <div class="sysprompt module">
        <div class="main cut">
            <i class="type-info>"></i>
            <h3 class="mt20 xauto"><?=$message?></h3>
            <p class="center c999 mt15">系统将在<font id="countdown" class="countdown"><?=$time?></font>秒后自动跳转</p>
        </div>
        <div class="act center mt20">
            <a href="<?=$this->url('./')?>">返回首页</a>
            <a href="<?=$url?>">直接跳转</a>
        </div>
    </div>
</div>