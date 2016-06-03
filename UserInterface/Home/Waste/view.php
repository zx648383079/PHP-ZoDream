<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\Response\View */
use Zodream\Domain\Authentication\Auth;
$this->extend(array(
    'layout' => array(
        'head',
        'navbar'
    )), array(
        'zodream/blog.css'
    )
);
$data = $this->get('data');
$links = $this->get('links');
$comment = $this->get('comment', array());
?>

<div class="container">
    
    <div id="title" class="row">
        <h1 class="text-center"><?php echo $data['title'];?></h1>
    </div>
    
    <div class="row">
        作者： <?php echo $data['user'];?>
        发表时间：<?php $this->time($data['create_at']);?>
        <?php if (!Auth::guest() && Auth::user()['id'] == $data['user_id']) {?>
        <a href="<?php $this->url('admin.php/post/add/id/'.$data['id']);?>">编辑</a>
        <?php }?>
    </div>
    
    <div id="content" class="row">
        <?php echo htmlspecialchars_decode($data['content']);?>
    </div>
    
    
    <div id="plugin" class="row">
        <div class="recommend" data="<?php echo $data['id'];?>">
            <span><?php echo $data['recommend']?></span>
            <span>推荐</span>
        </div>
        <div class="bdsharebuttonbox"><a href="#" class="bds_more" data-cmd="more"></a><a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a><a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a><a href="#" class="bds_renren" data-cmd="renren" title="分享到人人网"></a><a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a></div>
        <script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"1","bdSize":"32"},"share":{},"image":{"viewList":["qzone","tsina","tqq","renren","weixin"],"viewText":"分享到：","viewSize":"32"},"selectShare":{"bdContainerClass":null,"bdSelectMiniList":["qzone","tsina","tqq","renren","weixin"]}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>
    </div>
    
    
    <div class="row">
        <div class="col-sm-6">
            <?php if (!empty($links[0])) {?>
            <a href="<?php echo $links[0]['id'];?>">
                <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>
                上一篇：<?php echo $links[0]['title'];?>
            </a>
            <?php }?>
        </div>
        <div class="col-sm-6 text-right">
            <?php if (!empty($links[1])) {?>
                <a href="<?php echo $links[1]['id'];?>">
                    下一篇：<?php echo $links[1]['title'];?>
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
            <?php if (!empty($comment)) {
                foreach ($comment as $key => $item) {?>
                <div class="comment-item">
                    <?php echo $key + 1;?>楼 
                    <?php $this->time($item['create_at']);?>
                    
                    <?php if (empty($item['url'])) {
                        echo $item['name'];
                    } else {?>
                    <a href="<?php echo $item['url'];?>" target="_blank"><?php echo $item['name'];?></a>
                    <?php }?>
                    <a class="reply" data="<?php echo $item['name'];?>" href="javascript:;">回复</a>
                    <?php if (!Auth::guest() && !empty($comment['user_id'])) {?>
                    <a href="<?php $this->url(['admin.php/message/send', 'id' => $comment['user_id']]);?>">私信</a>
                    <?php }?>
                    <div>
                        <?php echo $item['content'];?>
                    </div>
                </div>
            <?php }
            } else {?>
                <p>暂无评论，快来抢沙发~~~~</p>
            <?php }?>
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
                                <input type="text" name="name" id="input_name" class="form-control" value="<?php $this->ech('name');?>" required="required" >
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="input_email" class="col-sm-2 control-label">邮箱:</label>
                            <div class="col-sm-10">
                                <input type="email" name="email" id="input_email" class="form-control" value="<?php $this->ech('email');?>" required="required" >
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
        function() {?>
<script type="text/javascript">
require(['home/blog']);
</script>       
     <?php }
    )
);
?>