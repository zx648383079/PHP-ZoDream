<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Support\Html;
use Zodream\Service\Routing\Url;
/** @var $this \Zodream\Domain\View\View */
/** @var $model \Domain\Model\Shopping\AddressModel */
$this->extend('layout/header');
?>

<!-- 收货地址列表 --->
<div class="address">

    <?php foreach ($models as $item):?>
        <div class="addressItem">
            <div class="header">
                <span class="consignee">
                    <?=$item->consignee?>
                </span>
                <span class="tel">
                    <?=$item->telphone?>
                </span>
                <div class="action">
                    <?php if ($item->isDefault()):?>
                        默认
                    <?php else:?>
                        [<a href="<?=Url::to(['address/default', 'id' => $item->id])?>">设为默认</a>]
                    <?php endif;?>
                    [<a href="<?=Url::to(['address/add', 'id' => $item->id])?>">编辑</a>]
                    [<a href="<?=Url::to(['address/delete', 'id' => $item->id])?>">删除</a>]
                </div>
            </div>
            <div class="content">
                <?=$item->country?>
                <?=$item->province?>
                <?=$item->city?>
                <?=$item->district?>
                <?=$item->street?>
                <?=$item->address?>
            </div>
        </div>
    <?php endforeach;?>
</div>

<?php
$this->extend('layout/footer');
?>