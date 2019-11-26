<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Template\View */
$this->title = '历史记录';
?>
<div class="page-tip">
    <p class="blue">操作提示</p>
    <ul>
        <li>关注公众号时自动回复</li>
    </ul>
    <span class="toggle"></span>
</div>
<table>
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
                    <?php if($item->mark < 1):?>
                    <a data-type="ajax" href="<?=$this->url('./@admin/log/mark', ['id' => $item->id])?>">标记</a>
                    <?php endif;?>
                    <a data-type="del" href="<?=$this->url('./@admin/log/delete', ['id' => $item->id])?>">删除</a>
                </td>
           </tr>
        <?php endforeach;?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5">
                <?=$log_list->getLink()?>
            </td>
        </tr>
    </tfoot>
</table>
