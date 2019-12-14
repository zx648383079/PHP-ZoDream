<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '网站概况';
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

<div class="tab-header">
    <a href="" class="active" data-type="today">今天</a>
    <a href="" data-type="yesterday">昨天</a>
    <a href="" data-type="week">最近7天</a>
    <a href="" data-type="month">最近30天</a>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="panel">
            <div class="panel-header">趋势图</div>
            <div class="panel-body">
                <div>
                    <div class="btn-group">
                        <a href="" class="active">浏览量(PV)</a>
                        <a href="">访客数(UV)</a>
                    </div>
                    <div class="radio-group">
                        <label for="">对比：</label>
                        <input type="radio" name="">前一日
                        <input type="radio" name="">上周同期
                    </div>
                </div>
                <div id="trend-chart"></div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel">
            <div class="panel-header">Top10搜索词</div>
            <div class="panel-body">
                <table class="table">
                    <thead>
                        <tr>
                            <td class="al url">搜索词</td>
                            <td class="ar" width="20%">浏览量(PV)</td>
                            <td class="ratio" width="20%">占比</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="al url"><span class="ellipsis" title="wta即时排名">wta即时排名</span></td>
                            <td class="ar">15</td>
                            <td class="ratio">
                                <div title="22.73%" style="background-color:#DCEBFE; width:22.73%;">22.73%</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel">
            <div class="panel-header">Top10来源网站</div>
            <div class="panel-body">
                <table class="table">
                    <thead>
                        <tr>
                            <td class="al url">来源网站</td>
                            <td class="ar" width="20%">浏览量(PV)</td>
                            <td class="ratio" width="20%">占比</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="al url"><span class="ellipsis">直接访问</span></td>
                            <td class="ar">15</td>
                            <td class="ratio">
                                <div title="22.73%" style="background-color:#DCEBFE; width:22.73%;">22.73%</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel">
            <div class="panel-header">Top10入口页面</div>
            <div class="panel-body">
                <table class="table">
                    <thead>
                        <tr>
                            <td class="al url">入口页面</td>
                            <td class="ar" width="20%">浏览量(PV)</td>
                            <td class="ratio" width="20%">占比</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="al url"><a target="_blank" class="ellipsis" href="https://coric.top">https://coric.top</a></td>
                            <td class="ar">15</td>
                            <td class="ratio">
                                <div title="22.73%" style="background-color:#DCEBFE; width:22.73%;">22.73%</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel">
            <div class="panel-header">Top10受访页面</div>
            <div class="panel-body">
                <table class="table">
                    <thead>
                        <tr>
                            <td class="al url">受访页面</td>
                            <td class="ar" width="20%">浏览量(PV)</td>
                            <td class="ratio" width="20%">占比</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="al url"><a target="_blank" class="ellipsis" href="https://coric.top">https://coric.top</a></td>
                            <td class="ar">15</td>
                            <td class="ratio">
                                <div title="22.73%" style="background-color:#DCEBFE; width:22.73%;">22.73%</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel">
            <div class="panel-header">地域分布</div>
            <div class="panel-body">
                <div id="district-chart"></div>
            </div>
        </div>
    </div>
</div>