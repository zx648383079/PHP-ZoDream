<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<?php foreach ($comment_list as $reply) :?>
<div class="comment-item" data-id="<?=$parent_id?>">
    <div class="text">
        <a href=""><?=$reply['user']['name']?></a>：
        <?=$this->text($reply['content'])?>
    </div>
    <div class="actions">
        <span class="time"><?=$reply['created_at']?></span>
        <div class="tools">
            <a href="">回复</a>
            <a href="">赞</a>
        </div>
    </div>
  
</div>
<?php endforeach;?>