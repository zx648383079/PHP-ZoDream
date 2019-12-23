<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Helpers\Time;
/** @var $this View */
$this->title = 'ZoDream';
$maps = ['pv', 'uv', 'ip', 'jump', 'stay'];
?>
<div class="panel">
    <div class="panel-header">今日流量</div>
    <div class="panel-body">
        <table class="table">
            <tbody>
                <tr class="title">
                    <th></th>
                    <th>浏览量(PV)</th>
                    <th>访客数(UV)</th>
                    <th>IP数</th>
                    <th>跳出</th>
                    <th>平均访问时长</th>
                    <th>转化次数</th>
                    <th class="empty-tr0"></th>
                </tr>
                <tr class="highlight">
                    <td class="normal">今日</td>
                    <?php foreach($maps as $k):?>
                    <td class=""><?= $k === 'stay' ? Time::hoursFormat($today[$k]) : $today[$k] ?></td>
                    <?php endforeach;?>
                    <td class="">--</td>
                    <td class="empty-tr0"></td>
                </tr>
                <tr>
                    <td class="normal">昨日</td>
                    <?php foreach($maps as $k):?>
                    <td class=""><?= $k === 'stay' ? Time::hoursFormat($yesterday[$k]) : $yesterday[$k] ?></td>
                    <?php endforeach;?>
                    <td class="">--</td>
                    <td class="empty-tr0"></td>
                </tr>
                <tr>
                    <td class="normal">预计今日</td>
                    <?php foreach($maps as $k):?>
                    <?php if($expectToday[$k] > $yesterday[$k]):?>
                    <td class="arrow-up">
                    <?php elseif ($expectToday[$k] < $yesterday[$k]):?>         <td class="arrow-down">
                    <?php else:?>
                    <td class="">
                    <?php endif;?>
                    <?= $k === 'stay' ? Time::hoursFormat($expectToday[$k]) : $expectToday[$k]?></td>
                    <?php endforeach;?>
                    <td class="">--</td>
                    <td class="empty-tr0"></td>
                </tr>
                <tr class="empty-tr1"></tr>
                <tr class="fade toggleable-hidden empty-tr2">
                    <td colspan="9"></td>
                </tr>
                <tr class="fade toggleable-hidden">
                    <td class="normal">昨日此时</td>
                    <?php foreach($maps as $k):?>
                    <td class=""><?= $k === 'stay' ? Time::hoursFormat($yesterdayHour[$k]) : $yesterdayHour[$k] ?></td>
                    <?php endforeach;?>
                    <td class="">--</td>
                    <td class="empty-tr0"></td>
                </tr>
                <tr class="fade toggleable-hidden">
                    <td class="normal">每日平均</td>
                    <td class="">0</td>
                    <td class="">0</td>
                    <td class="">0</td>
                    <td class="">0</td>
                    <td class="">00:00:00</td>
                    <td class="">--</td>
                    <td class="empty-tr0"></td>
                </tr>
                <tr class="fade toggleable-hidden">
                    <td class="normal">历史峰值</td>
                    <td d="2019年11月14日" title="峰值日：2019/11/14" class="highlight-tip">0</td>
                    <td d="2019年11月18日" title="峰值日：2019/11/18" class="highlight-tip">0</td>
                    <td d="2019年11月18日" title="峰值日：2019/11/18" class="highlight-tip">0</td>
                    <td d="2019年11月18日" title="峰值日：2019/11/18" class="highlight-tip">0</td>
                    <td d="2019年12月10日" title="峰值日：2019/12/10" class="highlight-tip">00:00:00</td>
                    <td class="">--</td>
                    <td class="empty-tr0"></td>
                </tr>
                <tr class="fade toggleable-hidden empty-tr3">
                    <td colspan="9"></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>