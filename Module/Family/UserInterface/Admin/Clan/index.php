<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '族谱列表';
$canDo = auth()->user()->isAdministrator();
?>

<div class="panel-container">
    <div class="page-search-bar">
        <form class="form-horizontal" role="form">
            <div class="input-group">
                <label for="keywords">名称</label>
                <input type="text" class="form-control" name="keywords" id="keywords" placeholder="搜索名称">
            </div>
            <button type="submit" class="btn btn-default">搜索</button>
        </form>
        <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/clan/create')?>">新增族谱</a>
    </div>


    <table class="table table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>族谱名</th>
            <th>统计</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($items as $item):?>
            <tr>
                <td><?=$item->id?></td>
                <td><?=$item->name?></td>
                <td></td>
                <td>
                    <?php if($canDo):?>
                    <div class="btn-group">
                        <a class="btn btn-default" href="<?=$this->url('./@admin/family', ['clan_id' => $item->id])?>">查看</a>
                        <a class="btn btn-info" href="<?=$this->url('./@admin/clan/meta', ['clan_id' => $item->id])?>">附录</a>
                        <a class="btn btn-primary" href="<?=$this->url('./@admin/clan/edit', ['id' => $item->id])?>">编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/clan/delete', ['id' => $item->id])?>">删除</a>
                    </div>
                    <?php endif;?>
                    
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php if($items->isEmpty()):?>
        <div class="page-empty-tip">
            空空如也~~
        </div>
    <?php endif;?>
    <div align="center">
        <?=$items->getLink()?>
    </div>
</div>
