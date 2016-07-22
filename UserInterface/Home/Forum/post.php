<?php
defined('APP_DIR') or exit();
use Zodream\Domain\Authentication\Auth;
use Zodream\Infrastructure\Html;
/** @var $this \Zodream\Domain\Response\View */
$this->extend(array(
	'layout' => array(
		'head',
        'navbar'
	)), array (
        'zodream/blog.css'
    )
);
$page = $this->gain('page');
$data = $this->gain('data');
?>
<div class="container">
    
    <div class="row title">
        主题：<?=$data['title'];?> <a href="#post" class="btn btn-primary">回帖</a>
    </div>
    
    <?php foreach ($page->getPage() as $item) {?>
    <div class="row post">
        <div class="col-md-3 post-item">
            <img src="<?=$item['avatar'];?>" alt="<?=$item['name'];?>">
            <p>
                <?=$item['name'];?>
            </p>
        </div>
        <div class="col-md-9">
            <div class="post-head">
                <?php $this->ago($item['create_at']);?>
                <?php if ($item['first'] != 1) :?>
                    <?=Html::a('回复')?>
                <?php endif;?>
            </div>
            <div>
                <?=$item['content'];?>
                
                
                <?php if ($item['first'] == 1) :?>
                <div class="zan"><span class="glyphicon glyphicon-thumbs-up"></span></div>
                <?php endif;?>
            </div>
        </div>
    </div>
    <?php }?>
    <div class="row">
        <?php $page->pageLink();?>
    </div>
    
    <?php if (!Auth::guest()) {?>
    <div id="post" class="panel panel-default">
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