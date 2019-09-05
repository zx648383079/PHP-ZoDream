<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
?>

<div class="column-item">
    <div class="icon">
        <?=$today_count?>/<?=$yesterday_count?>
    </div>
    <div class="content">
        <h3>今日签到</h3>
        <p>昨日签到</p>
    </div>
</div>

<div class="column-item">
    <div class="icon">
        <?=$max_day?>
    </div>
    <div class="content">
        <h3>最长签到</h3>
    </div>
</div>
<div class="column-item">
    <div class="icon">
        <?=$avg_day?>
    </div>
    <div class="content">
        <h3>平均签到</h3>
    </div>
</div>