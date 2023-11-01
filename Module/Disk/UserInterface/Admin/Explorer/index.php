<?php
defined('APP_DIR') or exit();

use Zodream\Helpers\Disk;
use Zodream\Template\View;
/** @var $this View */

$this->title = '资源管理器';
?>

<div class="page-tooltip-bar">
    <p class="tooltip-header">操作提示</p>
    <ul>
        <li>当前路径：<?=$path?></li>
    </ul>
    <span class="tooltip-toggle"></span>
</div>

<div class="panel-container">
    <div class="page-search-bar">
        <form class="form-horizontal" role="form">
            <div class="input-group">
                <label class="sr-only" for="keywords">关键词</label>
                <input type="text" class="form-control" name="keywords" id="keywords" placeholder="搜索关键词" value="<?=$this->text($keywords)?>">
            </div>
            <div class="input-group">
                <label class="sr-only" for="tag">分区</label>
                <select class="form-control" name="tag">
                    <?php foreach($driveItems as $item):?>
                    <option value="<?=$item['path']?>" <?= str_starts_with($path, $item['path']) ? 'selected' : ''?>><?=$item['name']?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <button type="submit" class="btn btn-default">搜索</button>
        </form>
    </div>

    <table class="table table-hover">
        <thead>
        <tr>
            <th>文件名</th>
            <th>文件大小</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($items as $item):?>
            <tr>
                <td class="left" title="<?=$item['path']?>">
                    <i class="fa <?=$item['isFolder'] ? 'fa-folder' : 'fa-file'?>"></i>
                    <?=$item['name']?>
                </td>
                <td><?= $item['size'] ? Disk::size(intval($item['size'])) : '-'?></td>
                <td><?=$item['created_at'] ? $this->ago($item['created_at']) : '-'?></td>
                <td>
                    <div class="btn-group">
                        <?php if($item['isFolder']):?>
                        <a href="<?=$this->url('./@admin/explorer', ['path' => $item['path']])?>" class="btn btn-primary">进入</a>
                        <?php endif;?>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/explorer/delete', ['path' => $item['path']])?>">删除</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php if(empty($items) || (!is_array($items) && $items->isEmpty())):?>
        <div class="page-empty-tip">
            空空如也~~
        </div>
    <?php endif;?>
    <?php if(!is_array($items)):?>
    <div align="center">
        <?=$items->getLink()?>
    </div>
    <?php endif;?>
</div>