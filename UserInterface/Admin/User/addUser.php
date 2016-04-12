<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
$role = $this->get('role');
?>
<div>
    修改用户
</div>
<form method="POST">
    <div class="table">
        <div class="row">
            <div>
                用户名：
            </div>
            <div>
                <input type="hidden" name="id" value="<?php $this->ech('id');?>">
                <input type="text" name="name" value="<?php $this->ech('name');?>" placeholder="用户名">
            </div>
        </div>
        <div class="row">
            <div>
                邮箱：
            </div>
            <div>
                <input type="email" name="email" value="<?php $this->ech('email');?>" placeholder="邮箱">
            </div>
        </div>
        <div class="row">
            <div>
                密码：
            </div>
            <div>
                <input type="password" name="password" placeholder="密码">
            </div>
        </div>
        <div class="row">
            <div>
                确认密码：
            </div>
            <div>
                <input type="password" name="repassword" placeholder="确认密码">
            </div>
        </div>
        <div class="row">
            <div>
                角色：
            </div>
            <div>
                <select name="role">
                    <option disabled <?php echo empty($role)? 'selected' : '';?>>请选择角色</option>
                    <?php 
                    $this->swi(' selected');
                    foreach ($this->get('data', array()) as $value) {?>
                        <option value="<?php echo $value['id'];?>" <?php $this->cas($value['id'] == $role);?>><?php echo $value['name'];?></option>
                    <?php }?>
                </select>
            </div>
        </div>
        <div>
            <button type="submit">提交</button>
            <button type="reset">重置</button>
        </div>
    </div>
</form>

<?php
$this->extend(array(
        'layout' => array(
            'foot'
        ))
);
?>