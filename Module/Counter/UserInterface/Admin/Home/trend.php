<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Helpers\Time;
/** @var $this View */
?>
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