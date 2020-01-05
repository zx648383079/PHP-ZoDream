<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '搜索词';
?>
<form class="search-bar" action="" method="get">
    <label for="">时间：</label>
    <div class="tab-header">
        <a href="<?=$this->url(['start_at' => 'today'])?>" class="<?=!$start_at || $start_at === 'today' ? 'active' : ''?>" data-type="today">今天</a>
        <a href="<?=$this->url(['start_at' => 'yesterday'])?>" class="<?=$start_at === 'yesterday' ? 'active' : ''?>" data-type="yesterday">昨天</a>
        <a href="<?=$this->url(['start_at' => 'week'])?>" class="<?=$start_at === 'week' ? 'active' : ''?>" data-type="week">最近7天</a>
        <a href="<?=$this->url(['start_at' => 'month'])?>" class="<?=$start_at === 'month' ? 'active' : ''?>" data-type="month">最近30天</a>
    </div>
    <input type="text" readonly name="start_at">-
    <input type="text" readonly name="end_at">
    <button>搜索</button>
</form>
<table class="table table-hover">
<thead>
    <tr>
        <td rowspan="2">
            搜索词
        </td>
        <td colspan="3">
            网站基础指标
        </td>
        <td colspan="2">
            流量质量指标
        </td>
    </tr>
    <tr>
        <td>
            浏览量(PV)
        </td>
        <td>
            访客数(UV)
        </td>
        <td>
            IP数
        </td>
        <td>
            跳出率
        </td>
        <td>
            平均访问时长
        </td>
    </tr>
</thead>
<tbody>
    <?php foreach($items as $item):?>
        <tr>
            <td><?=$item['words']?></td>
            <td><?=$item['pv']?></td>
            <td><?=$item['uv']?></td>
            <td><?=$item['ip']?></td>
            <td>--</td>
            <td>--</td>
        </tr>
    <?php endforeach;?>
</tbody>
</table>