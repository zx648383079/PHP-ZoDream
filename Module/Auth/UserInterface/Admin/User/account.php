<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */

$this->title = '账户明细列表';
?>

<div class="page-search">
    <form class="form-horizontal" role="form">
        <div class="input-group">
            <label for="">渠道</label>
            <select name=""></select>
        </div>
        <button type="submit" class="btn btn-default">搜索</button>
    </form>
    <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/user/recharge', ['id' => $user->id])?>">手动充值</a>
</div>

<table class="table  table-bordered well">
    <thead>
    <tr>
        <th>ID</th>
        <th>时间</th>
        <th>渠道</th>
        <th>金额</th>
        <th class="auto-hide">账户余额</th>
        <th>备注</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($log_list as $item):?>
        <tr>
            <td><?=$item->id?></td>
            <td><?=$item->created_at?></td>
            <td><?=__('account_type.'.$item->type)?></td>
            <td><?=$item->money?></td>
            <td class="auto-hide"><?=$item->total_money?></td>
            <td><?=$item->remark?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div align="center">
    <?=$log_list->getLink()?>
</div>