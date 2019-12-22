<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
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
                    <th>跳出率</th>
                    <th>平均访问时长</th>
                    <th>转化次数</th>
                    <th class="empty-tr0"></th>
                </tr>
                <tr class="highlight">
                    <td class="normal">今日</td>
                    <td class="">963</td>
                    <td class="">308</td>
                    <td class="">307</td>
                    <td class="">55.35%</td>
                    <td class="">00:05:25</td>
                    <td class="">--</td>
                    <td class="empty-tr0"></td>
                </tr>
                <tr>
                    <td class="normal">昨日</td>
                    <td class="">5,803</td>
                    <td class="">1,356</td>
                    <td class="">1,410</td>
                    <td class="">49.01%</td>
                    <td class="">00:11:37</td>
                    <td class="">--</td>
                    <td class="empty-tr0"></td>
                </tr>
                <tr>
                    <td class="normal">预计今日</td>
                    <td class="arrow-down">2,291</td>
                    <td class="arrow-down">680</td>
                    <td class="arrow-up">706</td>
                    <td class="">--</td>
                    <td class="">--</td>
                    <td class="">--</td>
                    <td class="empty-tr0"></td>
                </tr>
                <tr class="empty-tr1"></tr>
                <tr class="fade toggleable-hidden empty-tr2">
                    <td colspan="9"></td>
                </tr>
                <tr class="fade toggleable-hidden">
                    <td class="normal">昨日此时</td>
                    <td class="">2,239</td>
                    <td class="">568</td>
                    <td class="">567</td>
                    <td class="">47.52%</td>
                    <td class="">00:13:40</td>
                    <td class="">--</td>
                    <td class="empty-tr0"></td>
                </tr>
                <tr class="fade toggleable-hidden">
                    <td class="normal">每日平均</td>
                    <td class="">16,445</td>
                    <td class="">3,387</td>
                    <td class="">3,566</td>
                    <td class="">44.64%</td>
                    <td class="">00:13:54</td>
                    <td class="">--</td>
                    <td class="empty-tr0"></td>
                </tr>
                <tr class="fade toggleable-hidden">
                    <td class="normal">历史峰值</td>
                    <td d="2019年11月14日" title="峰值日：2019/11/14" class="highlight-tip">59,240</td>
                    <td d="2019年11月18日" title="峰值日：2019/11/18" class="highlight-tip">16,690</td>
                    <td d="2019年11月18日" title="峰值日：2019/11/18" class="highlight-tip">17,305</td>
                    <td d="2019年11月18日" title="峰值日：2019/11/18" class="highlight-tip">64.31%</td>
                    <td d="2019年12月10日" title="峰值日：2019/12/10" class="highlight-tip">00:18:35</td>
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