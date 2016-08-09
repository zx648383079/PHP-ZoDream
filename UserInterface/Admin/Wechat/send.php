<?php
/** @var $this \Zodream\Domain\View\View */
defined('APP_DIR') or exit();
$this->extend(array(
    'layout' => array(
        'head'
    ))
);
?>

<div class="container">
    <div class="panel panel-default">
          <div class="panel-heading">
                <h3 class="panel-title">群发消息</h3>
          </div>
          <div class="panel-body">
                <form method="POST" class="form-horizontal" role="form">
                    <?php $this->extend('Wechat/editor');?>
                    
                    
                        
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <button type="submit" class="btn btn-primary">保存</button>
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