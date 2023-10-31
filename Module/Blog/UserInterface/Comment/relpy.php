<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<?php foreach ($comment_list as $reply) :?>
<div class="comment-item" data-id="<?=$parent_id?>">
    <div class="item-header">
        <span class="user"><?=$this->text($reply['name'])?></span>
        <span class="time"><?=$reply['created_at']?></span>
        <span class="floor"><?=$reply->position?>#</span>
    </div>
    <div class="item-body">
        <p><?=$this->text($reply['content'])?></p>
        <span>&nbsp;</span>
        <span class="comment" data-type="reply"><i class="fa fa-comment"></i></span>
        <span class="report"><?=__('Report')?></span>
        <div class="actions">
            <span class="agree"><i class="fas fa-thumbs-up"></i><b><?=$reply['agree_count']?></b></span>
            <span class="disagree"><i class="fas fa-thumbs-down"></i><b><?=$reply['disagree_count']?></b></span>
        </div>
    </div>
  
</div>
<?php endforeach;?>