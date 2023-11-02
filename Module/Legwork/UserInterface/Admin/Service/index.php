<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '服务列表';
?>

<div class="panel-container">
    <div class="page-search-bar">
        <form class="form-horizontal" role="form">
            <div class="input-group">
                <label class="sr-only" for="keywords">标题</label>
                <input type="text" class="form-control" name="keywords" id="keywords" placeholder="标题" value="<?=$this->text($keywords)?>">
            </div>
            <div class="input-group">
                <label>分类</label>
                <select name="cat_id" class="form-control">
                    <option value="">请选择</option>
                    <?php foreach($cat_list as $item):?>
                    <option value="<?=$item['id']?>" <?=$cat_id == $item['id'] ? 'selected': '' ?>>
                        <?=$item['name']?>
                    </option>
                    <?php endforeach;?>
                </select>
            </div>
            <button type="submit" class="btn btn-default">搜索</button>
        </form>
        <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/service/create')?>">新增服务</a>
    </div>

    <table class="table  table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>服务名</th>
            <th>分类</th>
            <th>简介</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($items as $item):?>
            <tr>
                <td><?=$item->id?></td>
                <td><?=$item->name?></td>
                <td><?=$item->category->name?></td>
                <td><?=$item->brief?></td>
                <td>
                    <div class="btn-group">
                        <a class="btn btn-default" href="<?=$this->url('./@admin/service/edit', ['id' => $item->id])?>">编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/service/delete', ['id' => $item->id])?>">删除</a>
                    </div>
                    
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