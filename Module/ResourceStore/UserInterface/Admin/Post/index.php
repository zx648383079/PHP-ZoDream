<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Helpers\Str;
/** @var $this View */

$this->title = '文章列表';
?>

<div class="page-search-bar">
    <form class="form-horizontal" role="form">
        <div class="input-group">
            <label for="keywords">标题</label>
            <input type="text" class="form-control" name="keywords" id="keywords" placeholder="搜索标题" value="<?=$this->text($keywords)?>">
        </div>
        <div class="input-group">
            <label>分类</label>
            <select name="cat_id">
                <option value="">请选择</option>
                <?php foreach($cat_list as $item):?>
                <option value="<?=$item['id']?>" <?=$cat_id == $item['id'] ? 'selected': '' ?>>
                    <?php if($item['level'] > 0):?>
                        ￂ<?=str_repeat('ｰ', $item['level'] - 1)?>
                    <?php endif;?>
                    <?=$item['name']?>
                </option>
                <?php endforeach;?>
            </select>
        </div>
        <button type="submit" class="btn btn-default">搜索</button>
    </form>
    <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/post/create')?>">新增文章</a>
</div>

<table class="table  table-bordered well">
    <thead>
    <tr>
        <th>ID</th>
        <th>标题</th>
        <th>分类</th>
        <th>统计</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($post_list as $item):?>
        <tr>
            <td><?=$item->id?></td>
            <td class="text-left">
            <?=Str::substr($item->title, 0, 20, true)?></td>
            <td>
                <?php if ($item->category):?>
                    <a href="<?=$this->url('./@admin/post', ['cat_id' => $item->cat_id])?>">
                        <?=$item->category->name?>
                    </a>
                <?php else:?>
                [未分类]
                <?php endif;?>
            </td>
            <td>
                下载：<?=$item->download?>
            </td>
            <td>
                <div class="btn-group  btn-group-xs">
                    <a class="btn btn-default btn-xs" href="<?=$this->url('./', ['id' => $item->id])?>" target="_blank">预览</a>
                    <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/post/edit', ['id' => $item->id])?>">编辑</a>
                    <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/post/delete', ['id' => $item->id])?>">删除</a>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div align="center">
    <?=$post_list->getLink()?>
</div>