<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '支付结果';
?>
<div class="pay-result-page">
    <div class="container">
        <?php if($log->status == 2):?>
        <div class="pay-success">
            <i class="fa fa-check-circle"></i>
            <p>您的订单  
                <span class="red"><?=$log->data?></span>
                已经支付成功，感谢您的参与！
            </p>
            <a href="<?=$this->url('./')?>" class="btn btn-primary">继续购买</a>
            <a href="<?=$this->url('./order', ['status' => 20])?>" class="btn btn-danger">查看订单</a>
        </div>
        <?php else:?>
        <div class="pay-failure">
            <h2>
                <i class="fa fa-times-circle"></i>
                您的订单支付没有成功！
            </h2>
            <p>请点击
                <a href="<?=$this->url('./order', ['status' => 10])?>" class="red">未完成的订单</a>
                继续支付，谢谢！
            </p>
        </div>
        <?php endif;?>
    </div>
</div>
