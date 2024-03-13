<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\Bot\Domain\Model\TemplateModel;
/** @var $this View */
$this->title = '微信模板消息模板';
?>
<div class="page-tooltip-bar">
    <p class="tooltip-header">操作提示</p>
    <ul>
        <li>微信模板消息模板</li>
    </ul>
    <span class="tooltip-toggle"></span>
</div>
<div class="panel-container">
    <div class="page-search-bar">
        <a class="btn btn-primary pull-right" data-type="ajax" href="<?=$this->url('./@admin/reply/refresh_template')?>">同步</a>
    </div>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>名称</th>
                <th>内容</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($model_list as $item):?>
            <tr>
                    <td><?=$item->template_id?></td>
                    <td><?=$item->title?></td>
                    <td>
                        <div class="rich_media_content" style="bot_idth: 320px">
                            <?=$item->content?>
                        </div>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="javascript:;" data-text="<?=$item->example?>" class="btn btn-info">预览</a>
                            <a data-type="del" href="<?=$this->url('./@admin/reply/delete_template', ['id' => $item->id])?>" class="btn btn-danger">删除</a>
                        </div>
                    </td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
    <?php if($model_list->isEmpty()):?>
    <div class="page-empty-tip">
        空空如也~~
    </div>
    <?php endif;?>
    <div align="center">
        <?=$model_list->getLink()?>
    </div>
</div>
