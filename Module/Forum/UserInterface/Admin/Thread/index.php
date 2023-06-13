<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Helpers\Str;
/** @var $this View */

$this->title = '帖子列表';
?>

<div class="page-search-bar">
    <form class="form-horizontal" role="form">
        <div class="input-group">
            <label for="keywords">标题</label>
            <input type="text" class="form-control" name="keywords" id="keywords" placeholder="搜索标题">
        </div>
        <div class="input-group">
            <label>板块</label>
            <select name="forum_id">
                <option value="">请选择</option>
                <?php foreach($forum_list as $item):?>
                <option value="<?=$item['id']?>" <?=$forum_id == $item['id'] ? 'selected': '' ?>>
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
</div>

<table class="table  table-bordered well">
    <thead>
    <tr>
        <th>ID</th>
        <th>标题</th>
        <th>板块</th>
        <th>统计</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($thread_list as $item):?>
        <tr>
            <td><?=$item->id?></td>
            <td>
                <?=Str::substr($item->title, 0, 20, true)?></td>
            <td>
                <?php if ($item->forum):?>
                    <a href="<?=$this->url('./@admin/thread', ['forum_id' => $item->forum_id])?>">
                        <?=$item->forum->name?>
                    </a>
                <?php else:?>
                [未分类]
                <?php endif;?>
            </td>
            <td>
                浏览：<?=$item->view_count?>/
                回复：<?=$item->post_count?>
            </td>
            <td>
                <div class="btn-group  btn-group-xs">
                    <a class="btn btn-default btn-xs" href="<?=$this->url('./thread', ['id' => $item->id])?>" target="_blank">查看</a>
                    <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/thread/delete', ['id' => $item->id])?>">删除</a>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div align="center">
    <?=$thread_list->getLink()?>
</div>