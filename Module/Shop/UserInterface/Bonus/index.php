<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
?>

<div class="user-page">
    <div class="container side-box">
        <div>
            <?php $this->extend('layouts/user_menu');?>
        </div>
        <div>
            <div>
                关于红包
            </div>
            <div class="tab-box">
                <div class="tab-header">
                    <div class="tab-item active">可使用</div>
                    <div class="tab-item">已使用</div>
                    <div class="tab-item">已失效</div>
                </div>
                <div class="tab-body">
                    <div class="tab-item active">

                    </div>
                    <div class="tab-item">

                    </div>
                    <div class="tab-item">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>