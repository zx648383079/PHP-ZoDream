<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '搜索';
$this->extend('../layouts/search_empty');
?>

<div class="has-header">
    <div class="search-recommend-box">
        <div class="panel">
            <div class="panel-header">
                <span>历史记录</span>
                <i class="fa fa-trash"></i>
            </div>
            <div class="panel-body">
                <a href="">111</a>
                <a href="">111111111</a>
            </div>
        </div>
        <div class="panel">
            <div class="panel-header">
                <span>热门搜索</span>
            </div>
            <div class="panel-body">
                <a href="">111</a>
                <a href="">111111111</a>
            </div>
        </div>
    </div>
    <ul class="search-tip-box">
        <li>
            <a href="">11111111111</a>
        </li>
        <li>
            <a href="">11111111111</a>
        </li>
    </ul>

</div>