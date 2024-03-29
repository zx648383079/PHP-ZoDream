<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Template\View */
$this->title = '历史记录';
?>
<div class="page-tooltip-bar">
    <p class="tooltip-header">操作提示</p>
    <ul>
        <li>关注公众号时自动回复</li>
    </ul>
    <span class="tooltip-toggle"></span>
</div>
<div class="panel-container">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>名称</th>
                <th>类型</th>
                <th>APPID</th>
                <th>说明</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($log_list as $item):?>
            <tr>
                    <td><?=$item->id?></td>
                    <td><?=$item->name?></td>
                    <td><?=$item->type?></td>
                    <td><?=$item->appid?></td>
                    <td><?=$item->message?></td>
                    <td>
                        <div class="btn-group">
                            <?php if($item->mark < 1):?>
                            <a data-type="ajax" href="<?=$this->url('./@admin/log/mark', ['id' => $item->id])?>" class="btn btn-default">标记</a>
                            <?php endif;?>
                            <a data-type="del" href="<?=$this->url('./@admin/log/delete', ['id' => $item->id])?>" class="btn btn-danger">删除</a>
                        </div>
                    </td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
    <?php if($log_list->isEmpty()):?>
    <div class="page-empty-tip">
        空空如也~~
    </div>
    <?php endif;?>
    <div align="center">
        <?=$log_list->getLink()?>
    </div>
</div>

