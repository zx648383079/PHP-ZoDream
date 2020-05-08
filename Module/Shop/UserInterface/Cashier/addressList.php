<?php
defined('APP_DIR') or exit();
/** @var $this View */
?>

<?php foreach($address_list as $item):?>
    <div class="address-item<?=$item->id == $selected ? ' active' : ''?>" data-id="<?=$item->id?>">
        <div>收货人: <?=$item->name?></div>
        <div>联系方式：<?=$item->tel?></div>
        <div>收货地址：<?=$item->region->full_name?> <?=$item->region->address?></div>
        <?php if($item->is_default):?>
        <span class="default">默认地址</span>
        <?php endif;?>
    </div>
<?php endforeach;?>
<?=$address_list->getLink()?>