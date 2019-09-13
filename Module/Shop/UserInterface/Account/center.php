<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '帐号安全';
?>
<div class="user-page">
    <div class="container side-box">
        <div>
            <?php $this->extend('layouts/user_menu');?>
        </div>
        <div class="panel history-panel">
            <div class="panel-header">
                <span>帐号安全</span>
            </div>
            <div class="panel-body">
                <?php foreach($model_list as $item):?>
                <div class="connect-item">
                    <div class="nmae">
                        <i class="fab <?=$item['icon']?>"></i>
                        <?=$item['name']?>
                        <?php if(isset($item['nickname'])):?>
                        (<?=$item['nickname']?>)
                        <?php endif;?>
                    </div>
                    <div class="action">
                        <?php if(isset($item['id'])):?>
                        <a data-type="del" href="<?=$this->url('./account/delete_connect', ['id' => $item['id']])?>">解绑</a>
                        <?php else:?>
                        <a href="<?=$this->url('./account')?>">绑定</a>
                        <?php endif;?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
