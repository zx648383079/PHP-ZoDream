<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '受访页面';
?>

<table class="table table-hover">
<thead>
    <tr>
        <td rowspan="2">
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
            <td><?=$item['host']?></td>
            <td><?=$item['pv']?></td>
            <td><?=$item['uv']?></td>
            <td><?=$item['ip']?></td>
            <td>--</td>
            <td>--</td>
        </tr>
    <?php endforeach;?>
</tbody>
</table>