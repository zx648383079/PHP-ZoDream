<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
?>


<table>
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
        <?php foreach($model_list as $item):?>
        <tr>
            <td>日期</td>
            <td>估计番茄钟总数</td>
            <td>实际完成番茄钟总数</td>
            <td>任务完成数</td>
            <td>中断数</td>
            <td>中止数</td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>