<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Helpers\Disk;
/** @var $this View */
$this->title = '本地主题';
?>
<?php if(!empty($current)):?>
<div class="panel">
    <div class="panel-header">当前主题</div>
    <div class="panel-body flex-panel">
        <div class="theme-item">
            <div class="thumb">
                <img src="<?=$this->url('./@admin/theme/cover',
                    ['theme' => $current['name']], false)?>" alt="">
            </div>
            <div class="name"><?=$current['name']?></div>
            <div class="desc"><?=$current['description']?></div>
            <a data-type="del" data-tip="是否确定备份此主题？" href="<?=$this->url('./@admin/theme/bak', false)?>" class="btn btn-danger">备份</a>
        </div>
        <?php if(!empty($bakItems)):?>
        <div class="bak-panel">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>备份主题</th>
                        <th>文件大小</th>
                        <th>备份时间</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($bakItems as $item):?>
                    <tr>
                        <td class="left"><?=$item['description']?></td>
                        <td><?=Disk::size($item['size'])?></td>
                        <td title="<?= $this->time($item['created_at']) ?>"><?=$this->ago($item['created_at'])?></td>
                        <td>
                            <a data-type="del" data-tip="是否回滚此备份？" href="<?=$this->url('./@admin/theme/bak_restore', ['file' => $item['name']], false)?>" class="btn btn-primary">恢复</a>
                        </td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
        <?php endif;?>
    </div>
</div>
<?php endif;?>

<div class="panel">
    <div class="panel-header">本地主题</div>
    <div class="panel-body">
        <?php foreach($themes as $item):?>
        <div class="theme-item">
            <div class="thumb">
                <img src="<?=$this->url('./@admin/theme/cover',
                    ['theme' => $item['name']], false)?>" alt="">
            </div>
            <div class="name"><?=$item['name']?></div>
            <div class="desc"><?=$item['description']?></div>
            <?php if(empty($current['name']) || $current['name'] !== $item['name']):?>
            <a data-type="del" data-tip="是否确定清空数据并使用此主题？" href="<?=$this->url('./@admin/theme/apply',
                ['theme' => $item['name']], false)?>" class="btn btn-default">使用</a>
            <?php endif;?>
        </div>
        <?php endforeach;?>
    </div>
</div>
