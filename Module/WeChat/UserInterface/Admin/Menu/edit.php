<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '编辑菜单';
?>

<div class="page-tip">
    <p class="blue">操作提示</p>
    <ul>
        <li>编辑菜单</li>
    </ul>
    <span class="toggle"></span>
</div>

<div>
    <form class="form-inline" data-type="ajax" action="<?=$this->url('./admin/menu/save')?>" method="post">
        <div class="input-group">
            <label for="name">菜单名</label>
            <input type="text" id="name" name="name" placeholder="菜单名" required value="<?=$model->name?>" size="100">
        </div>
        <div class="input-group">
            <label for="parent_id">上级菜单</label>
            <select name="parent_id" id="parent_id" required>
                <option value="0">顶级菜单</option>
                <?php foreach($menu_list as $item):?>
                    <option value="<?=$item->id?>" <?=$item->id == $model->parent_id ? 'selected' : ''?>><?=$item->name?></option>
                <?php endforeach;?>
            </select>
        </div>
        <?php $this->extend('../layouts/editor', [
            'tab_id' => false
        ]); ?>
        <button class="btn btn-primary">保存</button>
        <input type="hidden" name="id" value="<?=$model->id?>">
    </form>
</div>
