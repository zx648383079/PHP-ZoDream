<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '评论列表';
?>

<div class="panel-container">
    <div class="page-search-bar">
        <form class="form-horizontal" role="form">
            <div class="input-group">
                <label class="sr-only" for="keywords">内容</label>
                <input type="text" class="form-control" name="keywords" id="keywords" placeholder="标题">
            </div>
            <button type="submit" class="btn btn-default">搜索</button>
        </form>
    </div>

    <table class="table table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>昵称</th>
            <th>邮箱</th>
            <th>内容</th>
            <th>文章</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($comment_list as $item):?>
            <tr>
                <td><?=$item->id?></td>
                <td class="left">
                    <a href="<?=$item->url?>" target="_blank">
                        <?=$this->text($item->name)?>
                    </a>
                </td>
                <td class="right">
                    <a href="<?=$this->url('./@admin/comment', ['email' => $item->email])?>">
                        <?=$this->text($item->email)?>
                    </a>
                </td>
                <td class="left"><?=$this->text($item->content)?></td>
                <td class="left">
                    <a href="<?=$this->url('./@admin/comment', ['blog_id' => $item->blog_id])?>">
                        <?=$this->text($item->blog->title)?>
                    </a>
                </td>
                <td>
                    <div class="btn-group">
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/comment/delete', ['id' => $item->id])?>">删除</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php if($comment_list->isEmpty()):?>
        <div class="page-empty-tip">
            空空如也~~
        </div>
    <?php endif;?>
    <div align="center">
        <?=$comment_list->getLink()?>
    </div>
</div>