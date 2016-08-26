<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\View\View */
$this->registerJs('require(["admin/flow"]);');
$this->extend('layout/head');
?>

<div class="container">
    <h1 class="text-center">欢迎使用 ZoDream CMS </h1>
    <div class="panel">
        <h3 class="head">我的状态</h3>
        <div>
            登录者:  <?=$name?>   ,所属用户组:  超级管理员
            这是您第 <?=$num?> 次登录，上次登录时间： <?=$this->time($date);?> ，登录IP： <?=$ip?>
        </div>
    </div>

    <table class="table table-hover">
        <thead>
        <tr>
            <th></th>
            <th>浏览次数(PV)</th>
            <th>独立访客(UV)</th>
            <th>IP</th>
            <th>新独立访客</th>
            <th>访问次数</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>今日</td>
        </tr>
        <tr>
            <td>昨日</td>
        </tr>
        <tr>
            <td>今日预计</td>
        </tr>
        <tr>
            <td>昨日此时</td>
        </tr>
        <tr>
            <td>近90日平均</td>
        </tr>
        <tr>
            <td>历史最高</td>
        </tr>
        <tr>
            <td>历史累计</td>

            <td>-</td>
            <td>-</td>
        </tr>
        </tbody>
    </table>

    <div class="row">
        <div class="col-md-2">
            <select id="status" class="form-control">
                <option>今天流量</option>
                <option>昨天流量</option>
                <option>本周流量</option>
                <option>上周流量</option>
                <option>本月流量</option>
                <option>上月流量</option>
                <option>今年流量</option>
                <option>去年流量</option>
                <option>全部流量</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <canvas id="chart"></canvas>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <ul class="list-group">
                <li class="list-group-item active">
                    搜索引擎外链
                </li>
                <?php foreach ($search[1] as $key => $item):?>
                <li class="list-group-item">
                    <span class="badge"><?=$item?></span>
                    <?=$key?>
                </li>
                <?php endforeach;?>
            </ul>
        </div>
        <div class="col-md-3">
            <h3>搜索关键字</h3>
            <div class="tag-box">
                <?php foreach ($search[0] as $key => $item):?>
                    <a style="font-size: <?=$item + 5?>px;"><?=$key?></a>
                <?php endforeach;?>
            </div>
        </div>
        <div class="col-md-3">
            <canvas id="os"></canvas>
        </div>
    </div>
</div>

<?=$this->extend('layout/foot')?>
