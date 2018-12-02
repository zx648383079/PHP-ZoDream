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
                <?php foreach($history_list as $item):?>
                <a href="<?=$this->url('./mobile/search', ['keywords' => $item])?>"><?=$item?></a>
                <?php endforeach;?>
            </div>
        </div>
        <div class="panel">
            <div class="panel-header">
                <span>热门搜索</span>
            </div>
            <div class="panel-body">
                <?php foreach($hot_list as $item):?>
                <a href="<?=$this->url('./mobile/search', ['keywords' => $item])?>"><?=$item?></a>
                <?php endforeach;?>
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