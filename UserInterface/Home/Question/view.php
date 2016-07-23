<?php
defined('APP_DIR') or exit();
use Zodream\Domain\Access\Auth;
use Zodream\Infrastructure\Html;
/** @var $this \Zodream\Domain\View\Engine\DreamEngine */
/** @var $page \Zodream\Domain\Html\Page */
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
        主题：<?=$data['title'];?> 
    </div>
    <div>
        <?=$item['content'];?>
    </div>
    <?php foreach ($page->getPage() as $item) {?>
    <div class="row post">
        <div class="col-md-9">
            <?=$item['content'];?>
        </div>
    </div>
    <?php }?>
    <div class="row">
        <?php $page->pageLink();?>
    </div>
    
    <?php if (!Auth::guest() && $data['status'] < 3) {?>
    <div id="post" class="panel panel-default">
          <div class="panel-body">
                <form method="POST" class="form-horizontal" role="form">
                    <input type="hidden" name="question_id" value="<?php echo $data['id'];?>">
                    <input type="hidden" name="parent_id" value="0">
                    <div class="form-group">
                        <label for="textarea_content" class="col-sm-2 control-label">内容:</label>
                        <div class="col-sm-10">
                            <textarea name="content" id="textarea_content" class="form-control" rows="3" required="required"></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <button type="submit" class="btn btn-primary">回答</button>
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