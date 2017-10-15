<?php foreach ($comment_list as $item) :?>
<div class="comment-item">
    <div class="info">
        <span class="user"><?=$item['name']?></span>
        <span class="time"><?=$item['created_at']?></span>
        <span class="floor">4楼</span>
    </div>
    <div class="content">
        <p><?=$item['content']?></p>
        <span>&nbsp;</span>
        <span class="comment"><i class="fa fa-comment"></i></span>
        <span class="report">举报</span>
    </div>
    <div class="actions">
        <span class="agree"><i class="fa fa-thumbs-o-up"></i><b><?=$item['agree']?></b></span>
        <span class="disagree"><i class="fa fa-thumbs-o-down"></i><b><?=$item['disagree']?></b></span>

    </div>
    <div class="comments">
        <div class="comment-item">
            <div class="info">
                <span class="user">admin</span>
                <span class="time">2017-2-15</span>
                <span class="floor">1#</span>
            </div>
            <div class="content">
                <p>1222222222222222222222</p>
                <span>&nbsp;</span>
                <span class="comment"><i class="fa fa-comment"></i></span>
                <span class="report">举报</span>
            </div>
            <div class="actions">
                <span class="agree"><i class="fa fa-thumbs-o-up"></i><b>5</b></span>
                <span class="disagree"><i class="fa fa-thumbs-o-down"></i><b>5</b></span>
            </div>
        </div>
        <div class="comment-item">
            <div class="info">
                <span class="user">admin</span>
                <span class="time">2017-2-15</span>
                <span class="floor">1#</span>
            </div>
            <div class="content">
                <p>1222222222222222222222</p>
                <span>&nbsp;</span>
                <span class="comment"><i class="fa fa-comment"></i></span>
                <span class="report">举报</span>
            </div>
            <div class="actions">
                <span class="agree"><i class="fa fa-thumbs-o-up"></i><b>5</b></span>
                <span class="disagree"><i class="fa fa-thumbs-o-down"></i><b>5</b></span>
            </div>
        </div>
    </div>
</div>
<?php endforeach;?>