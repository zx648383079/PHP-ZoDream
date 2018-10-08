<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream Bug';
?>

<table class="table  table-bordered well">
    <thead>
    <tr>
        <th>漏洞标题</th>
        <th>危害级别</th>
        <th>时间</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($model_list as $item):?>
        <tr>
            <td>
                [<?=$item->type?>]
                <?=$item->name?></td>
            <td>
                <?=$item->grade?>
            </td>
            <td>
                <?=$item->created_at?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div align="center">
    <?=$model_list->getLink()?>
</div>