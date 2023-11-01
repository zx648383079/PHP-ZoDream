<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '用户列表';
?>

<div class="panel-container">
    <div class="page-search-bar">
        <form class="form-horizontal" role="form">
            <div class="input-group">
                <label class="sr-only" for="keywords">用户名</label>
                <input type="text" class="form-control" name="keywords" id="keywords" placeholder="用户名">
            </div>
            <button type="submit" class="btn btn-default">搜索</button>
        </form>
        <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/user/create')?>">新增用户</a>
    </div>

    <table class="table table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>用户名</th>
            <th class="auto-hide">邮箱</th>
            <th class="auto-hide">性别</th>
            <th>余额</th>
            <th>状态</th>
            <!-- <th class="auto-hide">最近登陆时间</th> -->
            <th class="auto-hide">注册时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($user_list as $item):?>
            <tr>
                <td><?=$item['id']?></td>
                <td class="left"><?=$this->text($item['name'])?></td>
                <td class="left auto-hide"><?=$this->text($item['email'])?></td>
                <td class="auto-hide"><?=$item['sex_label']?></td>
                <td><?=$item['money']?></td>
                <td><?=$item['status']?></td>
                <!-- <td class="auto-hide"><?=null//$item['last_at']?></td> -->
                <td class="auto-hide"><?=$item['created_at']?></td>
                <td>
                    <div class="btn-group">
                        <a class="btn btn-info" href="<?=$this->url('./@admin/user/account', ['id' => $item['id']])?>">明细</a>
                        <?php if($item['id'] != auth()->id()):?>
                        <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/user/edit', ['id' => $item['id']])?>">编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/user/delete', ['id' => $item['id']])?>">删除</a>
                        <?php endif;?>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php if($user_list->isEmpty()):?>
    <div class="page-empty-tip">
        空空如也~~
    </div>
    <?php endif;?>
    <div align="center">
        <?=$user_list->getLink()?>
    </div>
</div>