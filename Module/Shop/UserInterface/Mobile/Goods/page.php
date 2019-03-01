<?php foreach($comment_list as $item):?>
<div class="comment-item">
    <div class="item-header">
        <div class="avatar">
            <img src="<?=$item->user->avatar?>" alt="">
        </div>
        <div class="name"><?=$item->user->name?></div>
        <div class="score">
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
        </div>
    </div>
    <div class="time">
        <span><?=$item->created_at?> </span>
        <div class="attr">
        规格:雾白
        </div>
    </div>
    <div class="content"><?=$item->content?></div>
    <ul class="image-box">
        <?php foreach($item->images as $img):?>
        <li>
            <img src="<?=$img->image?>" alt="">
        </li>
        <?php endforeach;?>
    </ul>
</div>
<?php endforeach;?>