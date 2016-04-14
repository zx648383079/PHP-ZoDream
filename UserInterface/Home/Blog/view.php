<?php
use Zodream\Infrastructure\ObjectExpand\TimeExpand;
defined('APP_DIR') or exit();
$this->extend(array(
    'layout' => array(
        'head',
        'navbar'
    ))
);
$data = $this->get('data');
$links = $this->get('links');
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
            <h3 class="panel-title">所有评论</h3>
        </div>
        <div class="panel-body">
            
        </div>
    </div>
    
    <?php if ($data['comment_status'] == 'open') {?>
    <div class="panel panel-default">
          <div class="panel-heading">
                <h3 class="panel-title">发表评论</h3>
          </div>
          <div class="panel-body">
                <form action="" method="POST" class="form-horizontal" role="form">
                        <div class="form-group">
                            <label for="input_name" class="col-sm-2 control-label">姓名:</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" id="input_name" class="form-control" value="" required="required" >
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="input_email" class="col-sm-2 control-label">邮箱:</label>
                            <div class="col-sm-10">
                                <input type="email" name="email" id="input_email" class="form-control" value="" required="required" >
                            </div>
                        </div>
                        
                        
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