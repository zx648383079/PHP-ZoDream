<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Helpers\Str;
/** @var $this View */
$this->title = $book->name;
?>
   <div class="page-search-bar">
        <form class="form-horizontal" role="form">
            <div class="input-group">
                <label class="sr-only" for="keywords">标题</label>
                <input type="text" class="form-control" name="keywords" id="keywords" placeholder="标题" value="<?=$this->text($keywords)?>">
            </div>
            <button type="submit" class="btn btn-default">搜索</button>
            <input type="hidden" name="book" value="<?=$book->id?>">
        </form>
        <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/book/create_chapter', ['book' => $book->id])?>">新增章节</a>
    </div>

    <table class="table  table-bordered well">
        <thead>
        <tr>
            <th>ID</th>
            <th>章节名</th>
            <th>统计</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item):?>
            <tr>
                <td><?=$item->id?></td>
                <td>
                    <a href="<?=$this->url('./book/read', ['id' => $item->id])?>"><?=Str::substr($item->title, 0, 20)?></a>
                </td>
                <td>
                    <?=$item->size?>
                </td>
                <td>
                    <div class="btn-group  btn-group-xs">
                        <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/book/edit_chapter', ['id' => $item->id])?>">编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/book/delete_chapter', ['id' => $item->id])?>">删除</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div align="center">
        <?=$model_list->getLink()?>
    </div>