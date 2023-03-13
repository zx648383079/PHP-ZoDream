<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$items = [];
foreach($log_list as $item) {
    $w = date('w', $item->getAttributeSource('created_at'));
    $items[] = [
        'day' => $w < 1 ? 7 : intval($w),
        'length' => $item->time,
        'name' => $item->task->name
    ];
}
?>

<div class="week-box">
    <div class="week-header">
        <section>周一</section>
        <section>周二</section>
        <section>周三</section>
        <section>周四</section>
        <section>周五</section>
        <section>周六</section>
        <section>周日</section>
    </div>
    <div class="week-body">
        
    </div>
</div>

<script>
    var items = <?=json_encode($items)?>;
    var maps = {};
    $.each(items, function(i) {
        var left = (this.day - 1) * 200;
        var top = 0;
        if (maps[this.day]) {
            top = maps[this.day] * 40;
            maps[this.day] ++;
        } else{
            maps[this.day] = 1;
        }
        $('.week-box .week-body').append('<div class="task-item" style="left: '+ left +'px;top: ' + top +'px;" title="'+ this.name  +'">' + this.name +'</div>');
    });
</script>