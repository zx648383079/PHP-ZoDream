<?php
defined('APP_DIR') or exit();
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
    
    <div class="row">
        <h1 class="text-center"><?php echo $data['title'];?></h1>
    </div>
    
    <div class="row">
        作者： <?php echo $data['user'];?>
        发表时间：<?php $this->time($data['create_at']);?>
    </div>
    
    <div class="row">
        <?php echo htmlspecialchars_decode($data['content']);?>
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
    
    
    
    <div class="panel panel-default">
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
                    <a href="<?php echo $item['url'];?>"><?php echo $item['name'];?></a>
                    <?php }?>
                    <a href="#">回复</a>
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

</script>       
     <?php }
    )
);
?>