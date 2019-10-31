<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>

<table class="table-hover">
    <thead>
        <tr>
            <th>日期</th>
            <th>估计番茄钟总数</th>
            <th>实际完成番茄钟总数</th>
            <th>任务完成数</th>
            <th>中断数</th>
            <th>中止数</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($day_list as $item):?>
        <tr>
            <td><?= $item['day'] ?>
            <em><?= $item['week'] ?></em>
            </td>
            <td><?= $item['amount'] ?></td>
            <td><?= $item['success_amount'] ?></td>
            <td><?= $item['complete_amount'] ?></td>
            <td><?= $item['pause_amount'] ?></td>
            <td><?= $item['failure_amount'] ?></td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>