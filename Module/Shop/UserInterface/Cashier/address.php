<?php
defined('APP_DIR') or exit();
/** @var $this View */
?>
<div class="address-view">
    <div>
        <div>
            <i class="fa fa-map-marker"></i>
            默认地址
            <a href="<?=$this->url('./cashier/edit_address', ['id' => $address->id])?>" class="btn" data-action="edit">修改</a>
        </div>
        <div>
            <span>收 货 人: </span>
            <?=$address->name?></div>
        <div>
            <span>联系方式 : </span>    
            <?=$address->tel?></div>
        <div>
            <span>收货地址 : </span>    
            <?=$address->region->full_name?> <?=$address->address?></div>
    </div>
    <div class="right">
        <p><a href="">地址切换</a></p>
        <p><a href="<?=$this->url('./cashier/edit_address')?>" class="btn" data-action="edit">新建地址</a></p>
    </div>
</div>