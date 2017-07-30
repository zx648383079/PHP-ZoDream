<?php
use Zodream\Domain\View\View;
/** @var $this View */
$this->extend('layout/header');
?>


<div class="phone-box">
    <div class="marvel-device iphone6 black">
        <div class="top-bar"></div>
        <div class="sleep"></div>
        <div class="volume"></div>
        <div class="camera"></div>
        <div class="sensor"></div>
        <div class="speaker"></div>
        <div class="screen">
            
            <div class="bottom-menu">
                <i class="fa fa-keyboard-o"></i>
                <div class="menu-content">
                    <ul>
                        <li>
                            <span>嘻嘻</span>
                            <ul>
                                <li class="add-menu">+</li>
                                <li>12312</li>
                            </ul>
                        </li>
                        <li class="add-menu"><span>+</span></li>
                        <li class="add-menu"><span>+</span></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="home"></div>
        <div class="bottom-bar"></div>
    </div>
</div>


<?php
$this->extend('layout/footer');
?>