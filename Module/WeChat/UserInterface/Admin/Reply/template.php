<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\WeChat\Domain\Model\TemplateModel;
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
<div class="page-action">
    <a data-type="ajax" href="<?=$this->url('./@admin/reply/refresh_template')?>">同步</a>
</div>
<table>
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
                    <div class="rich_media_content" style="width: 320px">
                        <?=$item->content?>
                    </div>
                </td>
                <td>
                    <a href="javascript" data-text="<?=$item->example?>">预览</a>
                    <a data-type="del" href="<?=$this->url('./@admin/reply/delete_template', ['id' => $item->id])?>">删除</a>
                </td>
           </tr>
        <?php endforeach;?>
    </tbody>
</table>

<?=$model_list->getLink()?>
