<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Helpers\Str;
/** @var $this View */

$this->title = '文章列表';
?>

<div class="page-search">
    <form class="form-horizontal" role="form">
        <div class="input-group">
            <label for="keywords">标题</label>
            <input type="text" class="form-control" name="keywords" id="keywords" placeholder="搜索标题" value="<?=$this->text($keywords)?>">
        </div>
        <div class="input-group">
            <label>分类</label>
            <select name="term_id">
                <option value="0">请选择</option>
                <?php foreach($term_list as $item):?>
                <option value="<?=$item->id?>" <?=$item->id == $term_id ? 'selected' : ''?>><?=$item->name?></option>
                <?php endforeach;?>
            </select>
        </div>
        <button type="submit" class="btn btn-default">搜索</button>
    </form>
    <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/blog/create')?>">新增文章</a>
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
    <?php foreach($blog_list as $item):?>
        <tr>
            <td><?=$item->id?></td>
            <td class="text-left">
            <?php if($item->open_type == 2):?>
                <i class="fa fa-ban" title="此文档为草稿"></i>
            <?php elseif($item->open_type > 0):?>
                <i class="fa fa-lock" title="阅读需要满足条件"></i>
            <?php endif;?>
            [<?=$item->type == 1 ? '转载' : '原创'?>]
            <?=Str::substr($item->title, 0, 20, true)?></td>
            <td>
                <?php if ($item->term):?>
                    <a href="<?=$this->url('./@admin/blog', ['term_id' => $item->term_id])?>">
                        <?=$item->term->name?>
                    </a>
                <?php else:?>
                [未分类]
                <?php endif;?>
            </td>
            <td>
                推荐：<?=$item->recommend?>/
                评论：<a href="<?=$this->url('./@admin/comment', ['blog_id' => $item->id])?>">
                    <?=$item->comment_count?>
                </a>
            </td>
            <td>
                <div class="btn-group  btn-group-xs">
                    <a class="btn btn-default btn-xs" href="<?=$this->url('./', ['id' => $item->id])?>" target="_blank">预览</a>
                    <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/blog/edit', ['id' => $item->id])?>">编辑</a>
                    <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/blog/delete', ['id' => $item->id])?>">删除</a>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div align="center">
    <?=$blog_list->getLink()?>
</div>