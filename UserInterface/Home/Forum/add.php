<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\Response\View */
/** @var $page \Zodream\Domain\Html\Page */
$this->extend(array(
	'layout' => array(
		'head',
        'navbar'
	))
);
$sub = $this->get('sub', array());
$page = $this->get('page');
?>
<div class="container">
    <div class="panel panel-default">
          <div class="panel-heading">
                <h3 class="panel-title">发表帖子</h3>
          </div>
          <div class="panel-body">
                <form method="POST" class="form-horizontal" role="form">
                    <input type="hidden" name="forum_id" value="<?php $this->ech('id');?>">
                    
                    <div class="form-group">
                        <label for="input_title" class="col-sm-2 control-label">标题:</label>
                        <div class="col-sm-10">
                            <input type="text" name="title" id="input_title" class="form-control" value="" required="required" >
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
                            <button type="submit" class="btn btn-primary">发表</button>
                        </div>
                    </div>
                </form>
          </div>
    </div>
</div>
<?php
$this->extend(array(
	'layout' => array(
		'foot'
	))
);
?>