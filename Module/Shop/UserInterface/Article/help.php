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
        <div class="panel help-panel">
            <div class="panel-header">
            常见问题
            </div>
            <div class="panel-body">
                <ul>
                    <li>
                        <div class="question">问：网易严选的商品都是正品吗？</div>
                        <div class="answer">
                        答：网易严选秉承网易一贯的严谨态度，对商品的产地、工艺、原材料都严格把关，力求帮消费者甄选到最优质的商品，您可以放心选购。
                        </div>
                    </li>
                    <li>
                        <div class="question">问：网易严选的商品都是正品吗？</div>
                        <div class="answer">
                        答：网易严选秉承网易一贯的严谨态度，对商品的产地、工艺、原材料都严格把关，力求帮消费者甄选到最优质的商品，您可以放心选购。
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
