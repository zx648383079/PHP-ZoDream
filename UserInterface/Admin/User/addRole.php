<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\View\View */
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
$roles = $this->gain('roles', array());
?>

<div class="panel panel-default">
      <div class="panel-heading">
            <h3 class="panel-title">修改角色</h3>
      </div>
      <div class="panel-body">
            
            <form action="" method="POST" class="form-horizontal" role="form">
                    
                    <div class="form-group">
                        <label for="textarea_name" class="col-sm-2">权限名称：</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="id" value="<?php $this->out('id');?>">
                <input type="text" name="name" value="<?php $this->out('name');?>" placeholder="权限名称">
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label for="input_password" class="col-sm-2 control-label">权限：</label>
                        <div class="col-sm-10">
                            <?php foreach ($this->gain('data', array()) as $value) {?>
                                <div>
                                    <input type="checkbox" name="auth[]" value="<?php echo $value['id'];?>" <?php echo in_array($value['id'], $roles) ? 'checked' : '' ;?>><?php echo $value['name'];?>
                                </div>
                            <?php }?>
                        </div>
                    </div>
                    
            
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <button type="submit" class="btn btn-primary">提交</button>
                            <button type="reset" class="btn btn-danger">重置</button>
                            <input id="selectAll" type="checkbox">全选
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