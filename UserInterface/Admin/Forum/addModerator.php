<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\View\Engine\DreamEngine */
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
$roles = $this->gain('roles', array());
?>

<div>
    添加版主
</div>
<form method="POST">
    <div class="table">
        <div class="row">
            <div>
                权限名称：
            </div>
            <div>
                <input type="hidden" name="id" value="<?php $this->out('id');?>">
                <input type="text" name="name" value="<?php $this->out('name');?>" placeholder="权限名称">
            </div>
        </div>
        <div class="row">
            <div>
                权限：
            </div>
            <div class="column">
                <?php foreach ($this->gain('data', array()) as $value) {?>
                    <div>
                        <input type="checkbox" name="auth[]" value="<?php echo $value['id'];?>" <?php echo in_array($value['id'], $roles) ? 'checked' : '' ;?>><?php echo $value['name'];?>
                    </div>
                <?php }?>
            </div>
        </div>
        <div>
            <button type="submit">提交</button>
            <button type="reset">重置</button>
            <input id="selectAll" type="checkbox">全选
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