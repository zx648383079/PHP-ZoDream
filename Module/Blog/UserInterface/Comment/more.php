<?php foreach ($comment_list as $item) :?>
<div class="comment-item">
    <div class="info">
        <span class="user"><?=htmlspecialchars($item['name'])?></span>
        <span class="time"><?=$item['created_at']?></span>
        <span class="floor">4楼</span>
    </div>
    <div class="content">
        <p><?=htmlspecialchars($item['content'])?></p>
        <span>&nbsp;</span>
        <span class="comment"><i class="fa fa-comment"></i></span>
        <span class="report">举报</span>
    </div>
    <div class="actions">
        <span class="agree"><i class="fa fa-thumbs-o-up"></i><b><?=$item['agree']?></b></span>
        <span class="disagree"><i class="fa fa-thumbs-o-down"></i><b><?=$item['disagree']?></b></span>

    </div>
    <?php if ($item->replies):?>
    <div class="comments">
        <?php foreach ($item->replies as $reply) :?>
        <div class="comment-item">
            <div class="info">
                <span class="user"><?=htmlspecialchars($reply['name'])?></span>
                <span class="time"><?=$reply['created_at']?></span>
                <span class="floor">1#</span>
            </div>
            <div class="content">
                <p><?=htmlspecialchars($reply['content'])?></p>
                <span>&nbsp;</span>
                <span class="comment"><i class="fa fa-comment"></i></span>
                <span class="report">举报</span>
            </div>
            <div class="actions">
                <span class="agree"><i class="fa fa-thumbs-o-up"></i><b><?=$reply['agree']?></b></span>
                <span class="disagree"><i class="fa fa-thumbs-o-down"></i><b><?=$reply['disagree']?></b></span>
            </div>
        </div>
        <?php endforeach;?>
    </div>
    <?php endif;?>
</div>
<?php endforeach;?>