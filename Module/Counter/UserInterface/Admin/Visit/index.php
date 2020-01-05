<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '受访页面';
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

<table class="table table-hover table-wrap">
<thead>
    <tr>
        <td rowspan="2" width="50%">
            页面URL
        </td>
        <td colspan="2">
            网站基础指标
        </td>
        <td colspan="3">
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
            贡献下游浏览量
        </td>
        <td>
            退出页次数
        </td>
        <td>
            平均访问时长
        </td>
    </tr>
</thead>
<tbody>
    <?php foreach($items as $item):?>
        <tr>
            <td width="50%"><?=$this->text($item['url'])?></td>
            <td><?=$item['pv']?></td>
            <td><?=$item['uv']?></td>
            <td><?=$item['ip']?></td>
            <td>--</td>
            <td>--</td>
        </tr>
    <?php endforeach;?>
</tbody>
<tfoot>
    <tr>
        <td colspan="6"><?=$items->getLink()?></td>
    </tr>
</tfoot>
</table>