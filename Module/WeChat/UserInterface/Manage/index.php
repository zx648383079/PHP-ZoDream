<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Template\View */
$this->title = 'ZoDream';
$this->extend('layouts/header');
?>
<div class="page-tip">
    <p class="blue">操作提示</p>
    <ul>
        <li>关注公众号时自动回复</li>
    </ul>
    <span class="toggle"></span>
</div>
<div class="page-action">
    <a href="<?=$this->url('./manage/create')?>">添加</a>
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
        <?php foreach($model_list as $item):?>
           <tr>
                <td><?=$item->id?></td>
                <td><?=$item->name?></td>
                <td><?=$item->type?></td>
                <td><?=$item->appid?></td>
                <td><?=$item->description?></td>
                <td>
                    <a href="<?$this->url('./manage/edit', ['id' => $item->id])?>">编辑</a>
                    <a data-type="del" href="<?$this->url('./manage/delete', ['id' => $item->id])?>">删除</a>
                </td>
           </tr>
        <?php endforeach;?>
    </tbody>
</table>

<?php $this->extend('layouts/footer');?>