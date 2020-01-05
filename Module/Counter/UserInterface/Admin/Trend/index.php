<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Helpers\Time;
/** @var $this View */
$this->title = '实时访客';
?>

<form class="search-bar" action="" method="get">
    
    <button>搜索</button>
</form>

<table class="table table-hover table-wrap">
<thead>
    <tr>
        <td>
            访问时间
        </td>
        <td>
            地域
        </td>
        <td>
            来源
        </td>
        <td>
            入口文件
        </td>
        <td>
            搜索词
        </td>
        <td>访问ip</td>
        <td>访问标识码</td>
        <td>访问时长</td>
        <td>访问页数</td>
    </tr>
</thead>
<tbody>
    <?php foreach($items as $item):?>
        <tr>
            <td><?=$this->time($item['enter_at'])?></td>
            <td></td>
            <td><?=$item['url']?></td>
            <td></td>
            <td></td>
            <td><?=$item['ip']?></td>
            <td><?=$item['session_id']?></td>
            <td>
                <?php if($item['leave_at'] > 0):?>
                    <?=Time::hoursFormat($item['leave_at'] - $item['enter_at'])?>
                <?php else: ?>
                --
                <?php endif;?>
            </td>
            <td>--</td>
        </tr>
    <?php endforeach;?>
</tbody>
<tfoot>
    <tr>
        <td colspan="6"><?=$items->getLink()?></td>
    </tr>
</tfoot>
</table>