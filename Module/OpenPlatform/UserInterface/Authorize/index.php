<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '我的授权信息';
?>
<div class="page-search">
    <?php if(!empty($platform_list)):?>
    <form class="form-horizontal" action="<?=$this->url('./authorize/save')?>" role="form" method="POST" data-type="ajax">
        <div class="input-group">
            <label class="sr-only" for="platform_id">应用</label>
            <select name="platform_id" id="platform_id">
                <?php foreach($platform_list as $item):?>
                <option value="<?=$item->id?>"><?=$item->name?></option>
                <?php endforeach;?>
            </select>
        </div>
        <button type="submit" class="btn btn-default">新建</button>
    </form>
    <?php endif;?>
    <a class="btn btn-success pull-right" data-tip="确定清除所有？" data-type="del" href="<?=$this->url('./authorize/clear')?>">清除所有</a>
</div>

<table class="table  table-bordered well">
    <thead>
    <tr>
        <th>ID</th>
        <th>应用名</th>
        <th>Token</th>
        <th>状态</th>
        <th>过期时间</th>
        <th>授权时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($model_list as $item):?>
        <tr>
            <td><?=$item->id?></td>
            <td><?=$item->platform->name?></td>
            <td><?=$item->is_self > 0 ? $item->token : '[不允许查看]'?></td>
            <td>
                <?=$item->expired_at < time() ? '已过期' : '正常'?>
            </td>
            <td>
                <?=$this->time($item->expired_at)?>
            </td>
            <td>
                <?=$item->created_at?>
            </td>
            <td>
                <div class="btn-group  btn-group-xs">
                    <a class="btn btn-danger" data-type="del" href="<?=$this->url('./authorize/delete', ['id' => $item->id])?>">删除</a>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div align="center">
    <?=$model_list->getLink()?>
</div>