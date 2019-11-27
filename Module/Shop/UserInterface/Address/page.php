<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<?php foreach($model_list as $item):?>
<div class="address-row">
    <div><?=$item->name?></div>
    <div><?=$item->region->full_name?> <?=$item->region->address?></div>
    <div><?=$item->tel?></div>
    <div>
        <a data-action="edit" href="<?=$this->url('./address/edit', ['id' => $item->id])?>">编辑</a>
        <a data-action="del" href="<?=$this->url('./address/delete', ['id' => $item->id])?>">删除</a>
    </div>
    <div>
        <?php if($item->is_default):?>
            <span class="btn">默认地址</span>
        <?php else:?>
            <a data-action="default" href="<?=$this->url('./address/default', ['id' => $item->id])?>">设为默认地址</a> 
        <?php endif;?>
    </div>
</div>
<?php endforeach;?>
<?=$model_list->getLink()?>