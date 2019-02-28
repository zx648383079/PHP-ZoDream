<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '评价晒单';
$js = <<<JS
bindComment();
JS;
$this->registerJs($js)
    ->registerJsFile('@jquery.upload.min.js')
    ->extend('../layouts/header');
?>
<div class="has-header">
    <?php foreach($goods_list as $goods):?>
    <form method="POST" action="<?=$this->url('./mobile/comment/save')?>" class="comment-form-item">
        <div class="goods-img">
            <img src="<?=$goods->thumb?>" alt="">
        </div>
        <div class="comment-star">
            <i class="fa fa-star active"></i>
            <i class="fa fa-star active"></i>
            <i class="fa fa-star active"></i>
            <i class="fa fa-star active"></i>
            <i class="fa fa-star active"></i>
            <input type="hidden" name="rank" value="5">
        </div>
        <div class="comment-input">
            <p>分享您的使用体验吧</p>
            <textarea name="content"></textarea>
            <div id="multi-image-box-<?=$goods->id?>" class="multi-image-box">
                <div class="add-item" data-grid="#multi-image-box-<?=$goods->id?>">
                    <i class="fa fa-plus"></i>
                </div>
            </div>
        </div>
        <div class="input-radio">
            <span>匿名评价</span>
            <i class="fa toggle-box"></i>
        </div>
        <button class="btn">提交评价</button>
        <input type="hidden" name="goods" value="<?=$goods->id?>">
    </form>
    <?php endforeach;?>
</div>