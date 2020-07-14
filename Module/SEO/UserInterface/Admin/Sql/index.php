<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Helpers\Disk;
use Zodream\Helpers\Time;
/** @var $this View */
$this->title = '数据备份管理';
?>

<a href="<?=$this->url('./@admin/sql/back_up')?>" data-type="ajax" class="btn">备份</a>
<a href="<?=$this->url('./@admin/sql/clear')?>" data-type="del" data-tip="确认清除所有备份" class="btn btn-danger">清除备份</a>

<table class="table table-hover">
    <thead>
        <tr>
            <th>文件名</th>
            <th>文件大小</th>
            <th>备份时间</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($items as $item):?>
        <tr>
            <td><?=$item['name']?></td>
            <td><?=Disk::size($item['size'])?></td>
            <td title="<?= Time::format($item['created_at']) ?>"><?=$this->ago($item['created_at'])?></td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>