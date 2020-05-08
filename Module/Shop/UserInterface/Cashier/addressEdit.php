<?php
defined('APP_DIR') or exit();
/** @var $this View */
?>
<div class="address-edit">
    <div class="edit-body">
        <div>*所在地区:</div>
        <div class="region-input" data-value="<?= $address ? $address->region_id : 0 ?>">
            <select name="" id=""></select>
            <select name="" id=""></select>
            <select name="" id=""></select>
        </div>
        <div>*详细地址:</div>
        <div>
            <textarea name="address" id="" cols="30" rows="10"><?= $address ? $address->address : '' ?></textarea>
        </div>
        <div>*收货人:</div>
        <div>
            <input type="text" name="name" value="<?= $address ? $address->name : '' ?>">
        </div>
        <div>*手机号码:</div>
        <div>
            <input type="text" name="tel" value="<?= $address ? $address->tel : '' ?>">
            <input type="hidden" name="id" value="<?= $address ? $address->id : 0 ?>">
        </div>
    </div>
    <div class="edit-right">
        <div>
            <div class="checkbox">
                <input type="checkbox" name="is_default" value="1" id="checkboxInput" <?= !$address || $address->is_default ? 'checked' : '' ?>>
                <label for="checkboxInput"></label>
            </div>
            设为默认
        </div>
        <button type="button" class="btn" data-action="save">保存地址</button>
        <?php if($prev > 0):?>
            <button type="button" class="btn btn-cancel" data-action="cancel" data-prev="<?=intval($prev)?>">取消</button>
        <?php endif;?>
    </div>
</div>