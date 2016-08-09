<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\View\View */
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
$role = $this->gain('role');
?>

<div class="panel panel-default">
      <div class="panel-heading">
            <h3 class="panel-title">修改用户</h3>
      </div>
      <div class="panel-body">
            
            <form action="" method="POST" class="form-horizontal" role="form">
                    
                    <div class="form-group">
                        <label for="textarea_name" class="col-sm-2 control-label">用户名：</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="id" value="<?php $this->out('id');?>">
                             <input type="text" name="name" class="form-control" value="<?php $this->out('name');?>" placeholder="用户名">
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label for="input_password" class="col-sm-2 control-label">邮箱：</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" name="email" value="<?php $this->out('email');?>" placeholder="邮箱">
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label for="input_newpassword" class="col-sm-2 control-label">密码： </label>
                        <div class="col-sm-10">
                            <input type="password" name="password" placeholder="密码" id="input_newpassword" class="form-control">
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label for="input_repassword" class="col-sm-2 control-label">确认密码： </label>
                        <div class="col-sm-10">
                            <input type="password" name="repassword" placeholder="确认密码" id="input_repassword" class="form-control">
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label for="input_role" class="col-sm-2 control-label">角色：</label>
                        <div class="col-sm-10">
                            <select name="role" id="input_role" class="form-control">
                                <option disabled <?php echo empty($role)? 'selected' : '';?>>请选择角色</option>
                                <?php 
                                $this->swi($role, ' selected');
                                foreach ($this->gain('data', array()) as $value) {?>
                                    <option value="<?php echo $value['id'];?>" <?php $this->cas($value['id']);?>><?php echo $value['name'];?></option>
                                <?php }?>
                            </select>
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