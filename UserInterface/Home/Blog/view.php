<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\View\Engine\DreamEngine */
use Zodream\Domain\Access\Auth;
use Zodream\Domain\Html\ShareWidget;
use Zodream\Infrastructure\Html;
$this->extend(array(
    'layout' => array(
        'head',
        'navbar'
    )), array(
        'zodream/blog.css'
    )
);
$data = $this->gain('data');
$links = $this->gain('links');
$comment = $this->gain('comment', array());
?>

<div class="container">
    
    <div id="title" class="row">
        <h1 class="text-center"><?=$data['title'];?></h1>
    </div>
    
    <div class="row">
        作者： <?php echo $data['user'];?>
        发表时间：<?php $this->time($data['create_at']);?>
        <?php if (!Auth::guest() && Auth::user()['id'] == $data['user_id']) :?>
        <a href="<?php $this->url('admin.php/post/add/id/'.$data['id']);?>">编辑</a>
        <?php endif;?>
    </div>
    
    <div id="content" class="row">
        <?php echo htmlspecialchars_decode($data['content']);?>
    </div>
    
    
    <div id="plugin" class="row">
        <div class="recommend" data="<?php echo $data['id'];?>">
            <span><?=$data['recommend']?></span>
            <span>推荐</span>
        </div>
        <?=ShareWidget::show()?>
    </div>
    
    
    <div class="row">
        <div class="col-sm-6">
            <?php if (!empty($links[0])) {?>
            <a href="<?=$links[0]['id'];?>">
                <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>
                上一篇：<?=$links[0]['title'];?>
            </a>
            <?php }?>
        </div>
        <div class="col-sm-6 text-right">
            <?php if (!empty($links[1])) {?>
                <a href="<?=$links[1]['id'];?>">
                    下一篇：<?=$links[1]['title'];?>
                    <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>
                </a>
            <?php }?>
        </div>
    </div>
    
    
    
    <div id="comment" class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">所有评论（<?php echo $data['comment_count'];?>）</h3>
        </div>
        <div class="panel-body">
            <?php if (!empty($comment)) :?>
                <?php foreach ($comment as $key => $item) :?>
                <div class="comment-item">
                    <?=$key + 1;?>楼
                    <?php $this->time($item['create_at']);?>
                    <?php if (!empty($item['url'])) :?>
                        <a href="<?=$item['url'];?>" target="_blank"><?=$item['name'];?></a>
                    <?php elseif (!empty($item['user_id'])):?>
                        <?=Html::a($item['name'], ['account.php/show', 'id' => $item['user_id']])?>
                    <?php else:?>
                        <?=$item['name']?>
                    <?php endif;?>
                    <a class="reply" data="<?=$item['name'];?>" href="javascript:;" data="<?=$item['id']?>">回复</a>
                    <?php if (!Auth::guest() && !empty($item['user_id']) && $item['user_id'] != Auth::user()['id']) :?>
                        <?=Html::a('私信', ['account.php/message/send', 'id' => $item['user_id']])?>
                    <?php endif;?>
                    <div>
                        <?=$item['content'];?>
                    </div>
                </div>
            <?php endforeach;
            else:?>
                <p>暂无评论，快来抢沙发~~~~</p>
            <?php endif;?>
        </div>
    </div>
    
    <?php if ($data['comment_status'] == 'open') {?>
    <div class="panel panel-default">
          <div class="panel-heading">
                <h3 class="panel-title">发表评论</h3>
          </div>
          <div class="panel-body">
                <form method="POST" class="form-horizontal" role="form">
                        <input type="hidden" name="parent" value="0">
                        <input type="hidden" name="post_id" value="<?php echo $data['id'];?>">
                        <?php if (Auth::guest()) {?>
                        <div class="form-group">
                            <label for="input_name" class="col-sm-2 control-label">姓名:</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" id="input_name" class="form-control" value="<?php $this->out('name');?>" required="required" >
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="input_email" class="col-sm-2 control-label">邮箱:</label>
                            <div class="col-sm-10">
                                <input type="email" name="email" id="input_email" class="form-control" value="<?php $this->out('email');?>" required="required" >
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="input_url" class="col-sm-2 control-label">网址:</label>
                            <div class="col-sm-10">
                                <input type="text" name="url" id="input_url" class="form-control" value="">
                            </div>
                        </div>
                        
                        <?php }?>
                        
                        <div class="form-group">
                            <label for="textarea_content" class="col-sm-2 control-label">内容:</label>
                            <div class="col-sm-10">
                                <textarea name="content" id="textarea_content" class="form-control" rows="3" required="required"></textarea>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                                <button type="submit" class="btn btn-primary">评论</button>
                            </div>
                        </div>
                </form>
          </div>
    </div>
    <?php }?>
    
</div>
<?php
$this->extend(array(
    'layout' => array(
        'foot'
    )), array(
        '!js require(["home/blog"]);'
    )
);
?>