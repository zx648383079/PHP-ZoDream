<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\View\View */
/** @var $page \Zodream\Domain\Html\Page */
$this->title = $title;
$this->extend([
    'layout/header',
    'layout/navbar'
]);
?>
<div class="container">
    <div class="panel panel-default">
          <div class="panel-heading">
                <h3 class="panel-title">我要提问</h3>
          </div>
          <div class="panel-body">
                <form method="POST" class="form-horizontal" role="form">
                    
                    <div class="form-group">
                        <label for="input_title" class="col-sm-2 control-label">标题:</label>
                        <div class="col-sm-10">
                            <input type="text" name="title" id="input_title" class="form-control" value="" required="required" >
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label for="textarea_content" class="col-sm-2 control-label">描述:</label>
                        <div class="col-sm-10">
                            <textarea name="content" id="textarea_content" class="form-control" rows="6"></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <button type="submit" class="btn btn-primary">提问</button>
                        </div>
                    </div>
                </form>
          </div>
    </div>
</div>
<?php $this->extend('layout/footer')?>