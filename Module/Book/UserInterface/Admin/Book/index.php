<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '小说列表';
?>
   <div class="page-search">
        <form class="form-horizontal" role="form">
            <div class="input-group">
                <label class="sr-only" for="keywords">标题</label>
                <input type="text" class="form-control" name="keywords" id="keywords" placeholder="标题" value="<?=$this->text($keywords)?>">
            </div>
            <div class="input-group">
                <label>分类</label>
                <select name="cat_id">
                    <option value="">请选择</option>
                    <?php foreach($cat_list as $item):?>
                    <option value="<?=$item->id?>" <?=$cat_id == $item->id ? 'selected' : ''?>><?=$item->real_name?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="input-group">
                <label>作者</label>
                <select name="author_id">
                    <option value="">请选择</option>
                    <?php foreach($author_list as $item):?>
                    <option value="<?=$item->id?>" <?=$author_id == $item->id ? 'selected' : ''?>><?=$item->name?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="input-group">
                <label>分级</label>
                <select name="classify">
                    <?php foreach([
                                      '无分级',
                                      '成人级',
                                  ] as $key => $item):?>
                        <option value="<?=$key?>"  <?=$classify == $key ? 'selected' : ''?>><?=$item?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <button type="submit" class="btn btn-default">搜索</button>
        </form>
       <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/book/create')?>">新增小说</a>
       <a class="btn btn-success pull-right" data-type="ajax" href="<?=$this->url('./@admin/book/refresh')?>">整理小说</a>
    </div>

    <table class="table  table-bordered well">
        <thead>
        <tr>
            <th>ID</th>
            <th>标题</th>
            <th>分类</th>
            <th>作者</th>
            <th>统计</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item):?>
            <tr>
                <td><?=$item->id?></td>
                <td>
                    <a href="<?=$this->url('./book', ['id' => $item->id])?>"><?=$item->name?></a>
                </td>
                <td>
                    <?php if ($item->category):?>
                        <a href="<?=$this->url('./@admin/book', ['cat_id' => $item->cat_id])?>">
                            <?=$item->category->name?>
                        </a>
                    <?php else:?>
                    [未分类]
                    <?php endif;?>
                </td>
                <td>
                    <?php if ($item->author):?>
                        <a href="<?=$this->url('./@admin/book', ['author_id' => $item->author_id])?>">
                            <?=$item->author->name?>
                        </a>
                    <?php else:?>
                    [未分类]
                    <?php endif;?>
                </td>
                <td>
                    <?=$item->format_size?>
                </td>
                <td>
                    <div class="btn-group  btn-group-xs">
                        <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/book/chapter', ['book' => $item->id])?>">章节</a>
                        <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/book/edit', ['id' => $item->id])?>">编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/book/delete', ['id' => $item->id])?>">删除</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div align="center">
        <?=$model_list->getLink()?>
    </div>