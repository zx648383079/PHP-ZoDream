<?php
defined('APP_DIR') or exit();

use Zodream\Helpers\Disk;
use Zodream\Template\View;
/** @var $this View */

$this->title = '上传文件管理器';
$tagItems = ['全部', '公共', '内部'];
?>

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
                    <?php foreach($tagItems as $i => $item):?>
                    <option value="<?=$i?>" <?=$i == $tag ? 'selected' : ''?>><?=$item?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <button type="submit" class="btn btn-default">搜索</button>
        </form>
    </div>

    <table class="table table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th class="auto-hide">分区</th>
            <th>文件名</th>
            <th>文件大小</th>
            <th class="auto-hide">MD5</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($items as $item):?>
            <tr>
                <td><?=$item['id']?></td>
                <td class="auto-hide">[<?=$tagItems[$item['folder']]?>]</td>
                <td class="left" title="<?=$item['path']?>"><?=$item['name']?></td>
                <td><?=Disk::size(intval($item['size']))?></td>
                <td title="<?=$item['path']?>"><?=$item['md5']?></td>
                <td><?=$this->ago($item['created_at'])?></td>
                <td>
                    <div class="btn-group">
                        <a class="btn btn-primary" data-type="ajax" href="<?=$this->url('./@admin/explorer/storage_sync', ['id' => $item['id']])?>" title="更新文件信息">更新</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/explorer/storage_delete', ['id' => $item['id']])?>">删除</a>
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