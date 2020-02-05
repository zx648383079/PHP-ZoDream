<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '族人列表';
$canDo = auth()->user()->isAdministrator();
?>

<div class="page-search">
    <form class="form-horizontal" role="form">
        <div class="input-group">
            <label for="keywords">姓名</label>
            <input type="text" class="form-control" name="keywords" id="keywords" placeholder="搜索姓名">
        </div>
        <div class="input-group">
            <label>家族</label>
            <select name="clan_id">
                <option value="">请选择</option>
                <?php foreach($clan_list as $item):?>
                <option value="<?=$item->id?>"><?=$item->name?></option>
                <?php endforeach;?>
            </select>
        </div>
        <button type="submit" class="btn btn-default">搜索</button>
    </form>
    <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/family/create')?>">新增族人</a>
</div>


<table class="table  table-bordered well">
    <thead>
    <tr>
        <th>ID</th>
        <th>姓名</th>
        <th>家族</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($model_list as $item):?>
        <tr>
            <td><?=$item->id?></td>
            <td><?=$item->name?></td>
            <td>
                <?php if($item->clan):?>
                <?=$item->clan->name?>
                <?php endif;?>
            </td>
            <td>
                <?php if($canDo):?>
                <div class="btn-group  btn-group-xs">
                    <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/family/edit', ['id' => $item->id])?>">编辑</a>
                    <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/family/delete', ['id' => $item->id])?>">删除</a>
                </div>
                <?php endif;?>
                
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div align="center">
    <?=$model_list->getLink()?>
</div>
