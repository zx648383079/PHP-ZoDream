<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Template\View */
$this->extend('layout/header');
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
                <input type="hidden" name="id" value="<?=$id?>">
                <input type="text" name="name" value="<?=$name?>" placeholder="权限名称">
            </div>
        </div>
        <div class="row">
            <div>
                权限：
            </div>
            <div class="column">
                <?php foreach ($data as $value) :?>
                    <div>
                        <input type="checkbox" name="auth[]" value="<?$value['id'];?>" <?=in_array($value['id'], $roles) ? 'checked' : '' ;?>><?$value['name'];?>
                    </div>
                <?php endforeach;?>
            </div>
        </div>
        <div>
            <button type="submit">提交</button>
            <button type="reset">重置</button>
            <input id="selectAll" type="checkbox">全选
        </div>
    </div>
</form>

<?=$this->extend('layout/footer')?>