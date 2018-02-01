<?php
use Zodream\Template\View;
/** @var $this View */
$this->extend('layouts/header');
?>

<div class="page-tip">
    <p class="blue">操作提示</p>
    <ul>
        <li>无任何关键词时自动回复</li>
    </ul>
    <span class="toggle"></span>
</div>

<div>
    <form class="form-inline" data-type="ajax" action="<?=$this->url('./reply/save')?>" method="post">
        <div class="input-group">
            <label for="event">事件</label>
            <select name="event" id="event" required>
                <option value="">请选择</option>
                <?php foreach($menu_list as $item):?>
                <option value="<?=$item->id?>" <?=$item->id == $model->event ? 'selected' : ''?>><?=$item->name?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="input-group">
            <label for="keywords">关键字</label>
            <input type="text" id="keywords" name="keywords" placeholder="关键字" required value="<?=$model->keywords?>" size="100">
        </div>
        <?php $this->extend('layouts/editor'); ?>
        <button class="btn btn-primary">保存</button>
        <input type="hidden" name="id" value="<?=$model->id?>">
    </form>
</div>

<?php $this->extend('layouts/footer');?>