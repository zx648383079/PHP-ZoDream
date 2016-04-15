<?php
defined('APP_DIR') or exit();
use Zodream\Domain\Authentication\Auth;
$this->extend(array(
	'layout' => array(
		'head',
        'navbar'
	))
);
$page = $this->get('page');
$data = $this->get('data');
?>
<div class="container">
    
    <div class="row">
        <?php echo $data['title'];?>
    </div>
    
    <?php foreach ($page->getPage() as $item) {?>
    <div class="row">
        <div class="col-md-3">
            <?php echo $item['user_name'];?>
        </div>
        <div class="col-md-9">
            <?php echo $item['content'];?>
            <p>
                <span><?php $this->ago($item['create_at']);?></span>
                <?php if ($item['first'] == 1) {?>
                点赞
                <?php }?>
            </p>
        </div>
    </div>
    <?php }?>
    <div class="row">
        <?php $page->pageLink();?>
    </div>
    
    <?php if (!Auth::guest()) {?>
    <div class="panel panel-default">
          <div class="panel-body">
                <form method="POST" class="form-horizontal" role="form">
                    <input type="hidden" name="forum_id" value="<?php echo $data['forum_id'];?>">
                    <input type="hidden" name="thread_id" value="<?php echo $data['id'];?>">
                    
                    <div class="form-group">
                        <label for="textarea_content" class="col-sm-2 control-label">内容:</label>
                        <div class="col-sm-10">
                            <textarea name="content" id="textarea_content" class="form-control" rows="3" required="required"></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <button type="submit" class="btn btn-primary">发表</button>
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
	))
);
?>