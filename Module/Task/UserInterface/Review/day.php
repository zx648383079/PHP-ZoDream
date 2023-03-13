<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$items = [];
foreach($log_list as $item) {
    $items[] = [
        'start' => date('H:i', $item->getAttributeSource('created_at')),
        'length' => $item->time,
        'name' => $item->task->name
    ];
}
?>

<div class="time-box">
    <div class="time-header">
        <?php for($i = 0; $i < 24; $i ++):?>
        <section><?=$i < 10 ? '0'.$i : $i?>:00</section>
        <?php endfor;?>
    </div>
    <div class="time-body">
        
    </div>
</div>

<script>
    var items = <?=json_encode($items)?>;
    $.each(items, function(i) {
        var args = this.start.split(':');
        var left = (parseInt(args[0]) * 50 + parseInt(args[1]) * 5 / 6);
        $('.time-box .time-body').append('<div class="task-item" style="left: '+ left +'px;top: ' + i * 40 +'px; width: '+ (this.length / 60 * 5 / 6) + 'px" title="'+ this.name  +'">' + this.name +'</div>');
        if (i < 1) {
            $('.review-box').scrollLeft(left);
        }
    });
</script>