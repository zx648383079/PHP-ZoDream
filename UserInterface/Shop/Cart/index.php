<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Support\Html;
/** @var $this \Zodream\Domain\View\View */
/** @var $cart \Domain\ShoppingCart */
$this->extend('layout/header');
?>

<!-- 商品介绍 --->
 <div class="cart">
     <div class="header">
         <div>
             <input type="checkbox">
         </div>
         <div class="goods">
             商品
         </div>
         <div class="price">
             价格
         </div>
         <div class="number">
             数量
         </div>
         <div class="action">
             操作
         </div>
     </div>
     <?php foreach ($cart->getGoods() as $item):?>
         <div class="cartItem">
             <div>
                 <input type="checkbox">
             </div>
             <div class="goods">
                 <div>
                     <img src="<?=$item->thumb?>">
                 </div>
                 <p><img src="<?=$item->name?>"></p>
             </div>
             <div class="price">
                 <?=$item->price?>
             </div>
             <div class="number">
                 <input type="number" value="<?=$item->number?>">
             </div>
             <div class="action">
                 [删除][收藏]
             </div>
         </div>
     <?php endforeach;?>
     <div class="footer">
         <div>
             <input type="checkbox">
         </div>
         <div class="actons">
             <button>全选</button>
             <button>删除</button>
             <button>清空</button>
         </div>

         <div class="total">
             <?=$cart->getNumber()?> 件，共 <?=$cart->getTotal()?> 元
             <button>支付</button>
         </div>
     </div>
 </div>

<?php
$this->extend('layout/footer');
?>