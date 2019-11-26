<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '用户管理';
?>
<div class="page-tip">
    <p class="blue">操作提示</p>
    <ul>
        <li>关注用户管理</li>
    </ul>
    <span class="toggle"></span>
</div>
<div class="page-action">
    <a data-type="ajax" href="<?=$this->url('./@admin/user/refresh')?>">同步</a>
</div>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>微信ID</th>
            <th>昵称</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($model_list as $item):?>
           <tr>
                <td><?=$item->id?></td>
                <td><?=$item->openid?></td>
                <td><?=$item->user ? $item->user->nickname : ''?></td>
                <td><?=$item->status_label?></td>
                <td>
                    <?php if($item->status === 0):?>
                     <a href="<?=$this->url('./@admin/reply/all', ['user_id' => $item->id])?>">群发消息</a>
                    <?php endif;?>
                    <a data-type="del" href="<?=$this->url('./@admin/user/delete', ['id' => $item->id])?>">删除</a>
                </td>
           </tr>
        <?php endforeach;?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5">
                <?=$model_list->getLink()?>
            </td>
        </tr>
    </tfoot>
</table>
