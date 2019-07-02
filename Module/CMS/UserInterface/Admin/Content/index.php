<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
?>
   <div class="search">
        <form class="form-horizontal" role="form">
            <div class="input-group">
                <label class="sr-only" for="keywords">标题</label>
                <input type="text" class="form-control" name="keywords" id="keywords" placeholder="标题" value="<?=$this->text($keywords)?>">
            </div>
            <button type="submit" class="btn btn-default">搜索</button>
            <input type="hidden" name="cat_id" value="<?=$cat->id?>">
        </form>
        <a class="btn btn-success pull-right" href="<?=$this->url('./admin/content/create', ['cat_id' => $cat->id])?>">新增文章</a>
    </div>

    <table class="table  table-bordered well">
        <thead>
        <tr>
            <th>ID</th>
            <th>标题</th>
            <th>分类</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item):?>
            <tr>
                <td><?=$item['id']?></td>
                <td>
                    <a href="<?=$this->url('./content', ['category' => $item['cat_id'], 'id' => $item['id']])?>" target="_blank"><?=$item['title']?></a>
                </td>
                <td>
                    <?php if ($cat):?>
                        <a href="<?=$this->url('./admin/content', ['cat_id' => $cat->id])?>">
                            <?=$cat->title?>
                        </a>
                    <?php else:?>
                    [未分类]
                    <?php endif;?>
                </td>
                <td>
                    <div class="btn-group  btn-group-xs">
                        <a class="btn btn-default btn-xs" href="<?=$this->url('./admin/content/edit', ['id' => $item['id'], 'cat_id' => $item['cat_id']])?>">编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./admin/content/delete', ['id' => $item['id'], 'cat_id' => $item['cat_id']])?>">删除</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div align="center">
        <?=$model_list->getLink()?>
    </div>