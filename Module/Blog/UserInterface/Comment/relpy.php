<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<?php foreach ($comment_list as $reply) :?>
<div class="comment-item" data-id="<?=$parent_id?>">
    <div class="info">
        <span class="user"><?=$this->text($reply['name'])?></span>
        <span class="time"><?=$reply['created_at']?></span>
        <span class="floor"><?=$reply->position?>#</span>
    </div>
    <div class="content">
        <p><?=$this->text($reply['content'])?></p>
        <span>&nbsp;</span>
        <span class="comment" data-type="reply"><i class="fa fa-comment"></i></span>
        <span class="report">举报</span>
    </div>
    <div class="actions">
        <span class="agree"><i class="fas fa-thumbs-up"></i><b><?=$reply['agree']?></b></span>
        <span class="disagree"><i class="fas fa-thumbs-down"></i><b><?=$reply['disagree']?></b></span>
    </div>
</div>
<?php endforeach;?>