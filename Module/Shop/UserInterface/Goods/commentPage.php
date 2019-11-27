<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<?php foreach($comment_list as $item):?>
<div class="comment-item">
    <div class="user-box">
        <div class="avatar">
            <img src="<?=$item->user->avatar?>" alt="">
        </div>
        <div class="name"><?=$item->user->name?></div>
    </div>
    <div>
        <div class="score">
            <?php for($i = 0; $i < 10; $i += 2):?>
            <i class="fa fa-star <?=$i < $item['rank'] ? 'light' : ''?>"></i>
            <?php endfor;?>
        </div>
        <div class="attr">
        规格:雾白
        </div>
        <div class="content"><?=$item->content?></div>
        <ul class="image-box">
            <?php foreach($item->images as $img):?>
            <li>
                <img src="<?=$img->image?>" alt="">
            </li>
            <?php endforeach;?>
        </ul>
        <div class="time"><?=$item->created_at?></div>
    </div>
</div>
<?php endforeach;?>
<?=$comment_list->getLink()?>