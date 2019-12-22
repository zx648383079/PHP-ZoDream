<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '网站概况';
?>

<div class="template-lazy" data-url="<?=$this->url('./admin/home/today')?>">
    
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