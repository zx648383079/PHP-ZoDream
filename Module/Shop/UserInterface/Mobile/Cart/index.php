<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '我的购物车';

$this->extend('../layouts/header');
?>

<div class="has-header">
    <?php if($cart->isEmpty()):?>
        <?php $this->extend('./empty');?>
    <?php else:?>
        <?php $this->extend('./list');?>
    <?php endif;?>
</div>

<?php $this->extend('../layouts/navbar');?>