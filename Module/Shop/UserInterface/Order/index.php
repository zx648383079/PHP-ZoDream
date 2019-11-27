<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '我的订单';
$status_map = [
    10 => 'un_pay',
    20 => 'paid_un_ship',
    40 => 'shipped',
    60 => 'uncomment'
];
$js = <<<JS
bindOrder();
JS;
$this->registerJs($js);
?>
<div class="user-page">
    <div class="container side-box">
        <div>
            <?php $this->extend('layouts/user_menu');?>
        </div>
        <div>
           <div class="order-search">
               <div>
                    <div class="order-tab">
                            <?php foreach(['全部订单', 10 => '待付款', 20 => '待发货', 40 => '待收货', 60 => '待评价'] as $i => $item):?>
                            <a href="<?=$this->url('./order', ['status' => $i])?>" <?=$status == $i ? 'class="active"' : ''?>><?=$item?>
                                <?php if(isset($status_map[$i]) && $order_subtotal[$status_map[$i]] > 0):?>
                                  <em><?=$order_subtotal[$status_map[$i]]?></em>
                                <?php endif;?>
                            </a>
                            <?php endforeach;?>
                    </div>
               </div>
               <div class="search-box">
                   <input type="text">
                   <button>搜索</button>
               </div>
           </div>
           <div class="order-page-box">
               <?php $this->extend('./page');?>
           </div>
        </div>
    </div>
</div>

