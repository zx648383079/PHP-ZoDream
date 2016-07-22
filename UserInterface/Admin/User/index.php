<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\Response\View */
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
?>


<div class="panel panel-default">
      <div class="panel-heading">
            <h3 class="panel-title">修改资料</h3>
      </div>
      <div class="panel-body">
            
            <form action="" method="POST" class="form-horizontal" role="form">
                    
                    <div class="form-group">
                        <label for="textarea_name" class="col-sm-2">用户名：</label>
                        <div class="col-sm-10">
                            <?php $this->out('name');?>
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label for="input_password" class="col-sm-2 control-label">原密码：</label>
                        <div class="col-sm-10">
                            <input type="password" name="oldpassword" required placeholder="原密码" id="input_password" class="form-control" >
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label for="input_newpassword" class="col-sm-2 control-label">新密码: </label>
                        <div class="col-sm-10">
                            <input type="password" name="password" placeholder="新密码" id="input_newpassword" class="form-control" required="required">
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label for="input_repassword" class="col-sm-2 control-label">重复新密码： </label>
                        <div class="col-sm-10">
                            <input type="password" name="repassword" placeholder="重复新密码" id="input_repassword" class="form-control" required="required">
                        </div>
                    </div>
                    
            
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <button type="submit" class="btn btn-primary">提交</button>
                        </div>
                    </div>
            </form>
            
      </div>
</div>


<?php
$this->extend(array(
        'layout' => array(
            'foot'
        ))
);
?>