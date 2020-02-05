<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
?>
   <div class="page-search">
        <form class="form-horizontal" role="form">
            <div class="input-group">
                <label class="sr-only" for="keywords">标题</label>
                <input type="text" class="form-control" name="keywords" id="keywords" placeholder="标题">
            </div>
            <button type="submit" class="btn btn-default">搜索</button>
        </form>
        <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/author/create')?>">新增作者</a>
    </div>

    <table class="table  table-bordered well">
        <thead>
        <tr>
            <th>ID</th>
            <th>作者名</th>
            <th>简介</th>
            <th>统计</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item):?>
            <tr>
                <td><?=$item->id?></td>
                <td><?=$item->name?></td>
                <td>
                </td>
                <td>
                    
                </td>
                <td>
                    <div class="btn-group  btn-group-xs">
                        <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/book', ['author_id' => $item->id])?>" target="_blank">查看</a>
                        <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/author/edit', ['id' => $item->id])?>">编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/author/delete', ['id' => $item->id])?>">删除</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div align="center">
        <?=$model_list->getLink()?>
    </div>