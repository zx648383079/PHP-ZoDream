<?php foreach ($comment_list as $reply) :?>
<div class="comment-item" data-id="<?=$parent_id?>">
    <div class="info">
        <span class="user"><?=htmlspecialchars($reply['name'])?></span>
        <span class="time"><?=$reply['created_at']?></span>
        <span class="floor"><?=$reply->position?>#</span>
    </div>
    <div class="content">
        <p><?=htmlspecialchars($reply['content'])?></p>
        <span>&nbsp;</span>
        <span class="comment" data-type="reply"><i class="fa fa-comment"></i></span>
        <span class="report">举报</span>
    </div>
    <div class="actions">
        <span class="agree"><i class="fa fa-thumbs-o-up"></i><b><?=$reply['agree']?></b></span>
        <span class="disagree"><i class="fa fa-thumbs-o-down"></i><b><?=$reply['disagree']?></b></span>
    </div>
</div>
<?php endforeach;?>