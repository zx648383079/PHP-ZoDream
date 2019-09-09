<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '投资记录';
?>
<div>
    <div class="col-xs-12">
        <table class="table table-hover">
            <thead>
            <tr>
                <td>用户</td>
                <td>项目</td>
                <td>投资额</td>
                <td>预计收益</td>
                <td>实际到账</td>
            </tr>
            </thead>
            <tbody>
            <?php foreach($model_list as $item): ?>
                <tr>
                    <td>
                        <?=$item->user->name?>
                    </td>
                    <td>
                        <?=$item->product->name?>
                    </td>
                    <td>
                        <?=$item->money?>
                    </td>
                    <td>
                        <?=$item->income?>
                    </td>
                    <td>
                        <?=$item->status > 0 ? $item->real_money : 0?>
                    </td>
                </tr>
            <?php endforeach?>
            </tbody>
        </table>
    </div>
</div>
<div align="center">
    <?=$model_list->getLink()?>
</div>