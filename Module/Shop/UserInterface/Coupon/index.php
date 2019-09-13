<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '领券中心';
?>

<div class="coupon-page">
    <div class="container">

        <ul class="path">
            <li>
                <a href="<?=$this->url('./')?>">首页</a>
            </li>
            <li>领券中心</li>
        </ul>

        <div>
            <div class="coupon-item">
                <div class="thumb">
                    <img src="https://m.360buyimg.com/common/s310x310_jfs/t17425/6/1117130719/77250/b4afe949/5abb0fc0Nb0fd7afd.jpg" alt="">
                </div>
                <div class="info">
                    <div class="price">
                        <em>200</em>
                        <span class="limit">满499元可用</span>
                    </div>
                    <div class="rang">
                        仅可购买指定平板电脑商品
                    </div>
                </div>
                <div class="action">
                    <a href="">立即领取</a>
                </div>
            </div>
        </div>

    </div>
</div>