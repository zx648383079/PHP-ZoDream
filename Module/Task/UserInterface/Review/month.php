<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
$w = date('w', $start_at);
$offset = $w < 1 ? 7 : $w;
$max = intval(date('d', $end_at));
$items = [];
foreach($log_list as $item) {
    $d = intval(date('d', $item->getAttributeSource('created_at')));
    $items[$d][] = [
        'day' => $d,
        'length' => $item->time,
        'name' => $item->task->name
    ];
}
?>

<div class="month-box">
    <div class="month-header">
        <section>周一</section>
        <section>周二</section>
        <section>周三</section>
        <section>周四</section>
        <section>周五</section>
        <section>周六</section>
        <section>周日</section>
    </div>
    <div class="month-body">
        <?php for($i = 1; $i < $offset; $i ++):?>
        <div class="day-item">

        </div>
        <?php endfor;?>
        <?php for($i = 1; $i <= $max; $i ++):?>
        <div class="day-item">
            <span class="day-label"><?= $i < 10 ? '0'.$i : $i ?></span>
            <ul class="day-box">
                <?php if(isset($items[$i])):foreach($items[$i] as $item):?>
                    <li class="task-item"><?=$item['name']?></li>
                <?php endforeach;endif;?>
            </ul>
        </div>
        <?php endfor;?>
    </div>
</div>