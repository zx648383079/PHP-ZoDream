<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '角色列表';
?>

<div class="panel-container">
<div class="page-search-bar">
        <form class="form-horizontal" role="form">
            <div class="input-group">
                <label class="sr-only" for="keywords">角色名</label>
                <input type="text" class="form-control" name="keywords" id="keywords" placeholder="角色名">
            </div>
            <button type="submit" class="btn btn-default">搜索</button>
        </form>
        <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/role/create')?>">新增角色</a>
    </div>

    <table class="table table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>角色名</th>
            <th>别名</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($role_list as $item):?>
            <tr>
                <td><?=$item->id?></td>
                <td><?=$item->name?></td>
                <td><?=$item->display_name?></td>
                <td>
                    <div class="btn-group">
                        <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/role/edit', ['id' => $item->id])?>">编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/role/delete', ['id' => $item->id])?>">删除</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php if($role_list->isEmpty()):?>
    <div class="page-empty-tip">
        空空如也~~
    </div>
    <?php endif;?>
    <div align="center">
        <?=$role_list->getLink()?>
    </div>
</div>