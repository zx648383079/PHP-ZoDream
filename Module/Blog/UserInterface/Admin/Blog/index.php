<?php
defined('APP_DIR') or exit();

use Module\Blog\Domain\Middleware\BlogSeoMiddleware;
use Zodream\Template\View;
use Zodream\Helpers\Str;
/** @var $this View */

$this->title = '文章列表';
?>

<div class="panel-container">
    <div class="page-search-bar">
        <form class="form-horizontal" role="form">
            <div class="input-group">
                <label for="keywords">标题</label>
                <input type="text" class="form-control" name="keywords" id="keywords" placeholder="搜索标题" value="<?=$this->text($keywords)?>">
            </div>
            <div class="input-group">
                <label>分类</label>
                <select name="term_id" class="form-control">
                    <option value="0">请选择</option>
                    <?php foreach($term_list as $item):?>
                    <option value="<?=$item->id?>" <?=$item->id == $term_id ? 'selected' : ''?>><?=$item->name?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="input-group">
                <label>类型</label>
                <select name="type" class="form-control">
                    <option value="0">全部</option>
                    <?php foreach(['原创', '转载'] as $key => $item):?>
                    <option value="<?=$key + 1?>" <?=$key + 1 == $type ? 'selected' : ''?>><?=$item?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <button type="submit" class="btn btn-default">搜索</button>
        </form>
        <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/blog/create')?>">新增文章</a>
    </div>

    <table class="table table-hover">
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
        <?php foreach($items as $item):?>
            <tr>
                <td><?=$item->id?></td>
                <td class="text-left">
                <?php if($item->open_type == 2):?>
                    <i class="fa fa-ban" title="此文档为草稿"></i>
                <?php elseif($item->open_type > 0):?>
                    <i class="fa fa-lock" title="阅读需要满足条件"></i>
                <?php endif;?>
                [<?=$item->type == 1 ? '转载' : '原创'?>]
                <?=$this->text($item->title, 20)?></td>
                <td class="left">
                    <?php if ($item->term):?>
                        <a href="<?=$this->url('./@admin/blog', ['term_id' => $item->term_id])?>">
                            <?=$item->term->name?>
                        </a>
                    <?php else:?>
                    [未分类]
                    <?php endif;?>
                </td>
                <td class="left">
                    推荐：<?=$item->recommend_count?>/
                    评论：<a href="<?=$this->url('./@admin/comment', ['blog_id' => $item->id])?>">
                        <?=$item->comment_count?>
                    </a>
                </td>
                <td>
                    <div class="btn-group">
                        <a class="btn btn-info" href="<?=BlogSeoMiddleware::encodeUrl($item->id, $item->language)?>" target="_blank">预览</a>
                        <a class="btn btn-default" href="<?=$this->url('./@admin/blog/edit', ['id' => $item->id])?>">编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/blog/delete', ['id' => $item->id])?>">删除</a>
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