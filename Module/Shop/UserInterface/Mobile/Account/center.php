<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '账户安全';
$this->extend('../layouts/header');
?>
<div class="has-header">
    <div class="account-box">
        <?php foreach($model_list as $item):?>
        <div class="line-item">
            <span><i class="fab <?=$item['icon']?>"></i><?=$item['name']?></span>
            <span><?=isset($item['id']) ? '已绑定' : '未绑定'?></span>
            <i class="fa fa-chevron-right"></i>
        </div>
        <?php endforeach;?>
    </div>
</div>