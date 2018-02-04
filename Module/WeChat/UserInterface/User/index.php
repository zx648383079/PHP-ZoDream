<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Template\View */
$this->title = 'ZoDream';
$this->extend('layouts/header');
?>
<div class="page-tip">
    <p class="blue">操作提示</p>
    <ul>
        <li>关注用户管理</li>
    </ul>
    <span class="toggle"></span>
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
                <td><?=$item->user->nickname?></td>
                <td><?=$item->status_label?></td>
                <td>
                    <a data-type="del" href="<?=$this->url('./user/delete', ['id' => $item->id])?>">删除</a>
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

<?php $this->extend('layouts/footer');?>