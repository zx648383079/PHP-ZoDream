<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\AdSense\Domain\Repositories\AdRepository;
/** @var $this View */
$this->title = 'ZoDream Shop';
$js = <<<JS
bindHome();
JS;
$this->registerCssFile('@slider.min.css')
    ->registerJsFile('@jquery.slider.min.js')
    ->registerJs($js);
?>

<div class="banner">
    <div class="slider" data-height="420" data-auto="true">
        <div class="slider-previous">&lt;</div>
       <div class="slider-box">
           <ul>
                <?php foreach(AdRepository::banners() as $item):?>
                <li><img src="<?=$item['content']?>" width="100%" alt=""></li>
                <?php endforeach;?>
           </ul>
       </div>
       <div class="slider-next">&gt;</div>
   </div>
</div>
<div class="floor">
    <div class="container">
        <div class="template-lazy" data-url="<?=$this->url('./home/brand')?>" data-tpl="brand_tpl">
      
        </div>
    </div>
</div>
<div class="floor">
    <div class="container">
        <div class="template-lazy" data-url="<?=$this->url('./home/new')?>" data-tpl="new_tpl">
                    
        </div>
        
    </div>
</div>

<div class="floor floor-out">
    <div class="container">
        <div class="template-lazy" data-url="<?=$this->url('./home/best')?>" data-tpl="best_tpl">
        </div>
    </div>
</div>

<div class="template-lazy" data-url="<?=$this->url('./home/category')?>">
</div>

<div class="floor floor-out comment-floor">
    <div class="container">
        <div class="template-lazy" data-url="<?=$this->url('./home/comment')?>" data-tpl="comment_tpl">
        </div>
        
    </div>
</div>

<!-- 弹出 app 下载图 -->
<!-- <div class="app-down-guide">
    <div class="content" style="background: url(//yanxuan.nosdn.127.net/e074a1c3c359701e236e712516189125.png);">
        <i class="fa fa-close"></i>
    </div>
</div> -->
<?php $this->extend([
    './brand.tpl',
    './new.tpl',
    './best.tpl',
    './comment.tpl'
]);?>