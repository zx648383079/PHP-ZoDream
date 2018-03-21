<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->registerJsFile('@jquery.min.js')
  ->registerJsFile('@jquery.cookie.js')
  ->registerJsFile('@book.min.js');
?>
    <div class="Layout ft">
      <div class="center">
        <div class="bot_logo"><a href="/" title="<?=$this->site_name?>"><img src="/images/bot_logo.png" alt="<?=$this->site_name?>" /></a></div>
        <div class="link">
          <div class="z"><?=$this->site_name?></div>
          <div class="f"><span>版权声明：<?=$this->site_name?></span></div>
        </div>
      </div>
    </div>


    <div class="chapter-sidebar">
      <ul>
        <li>
          <a href="<?=$book->url?>">
            <i class="fa fa-list"></i>
            <span>目录</span>
          </a>
        </li>
        <li class="do-setting">
          <i class="fa fa-gear"></i>
          <span>设置</span>
          <div class="sidebar-panel">
            <i class="fa fa-close"></i>
            <ul>
              <li>
                <span>阅读主体</span>
                <div class="theme-box">
                  <span class="theme-0 active"></span>
                  <span class="theme-1"></span>
                  <span class="theme-2"></span>
                  <span class="theme-3"></span>
                  <span class="theme-4"></span>
                  <span class="theme-5"></span>
                </div>
              </li>
              <li>
                <span>正文字体</span>
                <div class="font-box">
                  <span>雅黑</span>
                  <span>宋体</span>
                  <span>楷书</span>
                  <span>启体</span>                  
                </div>
              </li>
              <li>
                <span>字体大小</span>
                  <div class="size-box">
                      <i class="fa fa-minus"></i>
                        <span class="lang">18</span>
                      <i class="fa fa-plus"></i>
                  </div>
                </li>
                <li>
                  <span>页面宽度</span>
                  <div class="width-box">
                    <i class="fa fa-minus"></i>
                        <span class="lang">800</span>
                      <i class="fa fa-plus"></i>
                  </div>
                </li>
            </ul>
          </div>
        </li>
        <li class="go-top">
          <i class="fa fa-arrow-up"></i>
        </li>
      </ul>
    </div>


   <?=$this->footer()?>
   </body>
</html>