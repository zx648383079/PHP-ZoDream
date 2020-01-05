<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Helpers\Time;
/** @var $this View */
?>
<div class="panel">
    <div class="panel-header">Top10入口页面</div>
    <div class="panel-body">
        <table class="table">
            <thead>
                <tr>
                    <td class="al url">入口页面</td>
                    <td class="ar" width="20%">浏览量(PV)</td>
                    <td class="ratio" width="20%">占比</td>
                </tr>
            </thead>
            <tbody>
            <?php foreach($items as $item):?>
                <tr>
                    <td class="al url"><a target="_blank" class="ellipsis" href="<?=$item['url']?>"><?=$item['url']?></a></td>
                    <td class="ar"><?=$item['pv']?></td>
                    <td class="ratio">
                        <div title="<?=$item['scale']?>" style="background-color:#DCEBFE; width:<?=$item['scale']?>;"><?=$item['scale']?></div>
                    </td> 
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>
</div>