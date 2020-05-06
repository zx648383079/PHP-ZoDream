<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<?php if(!$simple):?>
<div class="dialog-search">
    <form class="form-horizontal" action="<?=$this->url(false, false)?>" role="form">
        <div class="input-group">
            <label class="sr-only" for="keywords">名称</label>
            <input type="text" class="form-control" name="keywords" id="keywords" placeholder="名称" value="<?=$this->text($keywords)?>">
        </div>
        <div class="input-group">
            <label>分组</label>
            <select name="group">
                <option value="0">请选择</option>
            </select>
        </div>
        <div class="input-group">
            <input type="checkbox" name="just_selected" value="1" <?=$just_selected ? 'checked' : ''?>>只显示选中
        </div>
        <button type="submit" class="btn btn-default">搜索</button>
        <input type="hidden" name="simple" value="1">
    </form>
</div>
<div class="dialog-body-box">
<?php endif;?>
    <div class="items-list">
        <?php foreach($model_list as $item):?>
        <a href="javascript:;" title="<?=$item->name?>" class="item<?=in_array($item->id, $selected) ? ' selected' : ''?>" data-id="<?=$item->id?>">
            <div class="thumb">
                <img src="<?=$item->thumb?>" alt="">
            </div>
            <div class="name"><?=$item->name?></div>
        </a>
        <?php endforeach;?>
    </div>
    <div class="dialog-pager">
        <?=$model_list->getLink()?>
    </div>
<?php if(!$simple):?>
</div>
<?php endif;?>