<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '搜索词';
?>
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