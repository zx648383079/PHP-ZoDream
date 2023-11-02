<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '消费渠道';
?>

<div class="panel-contianer">
    <h2>增加消费渠道</h2>
    <form data-type="ajax" action="<?=$this->url('./income/save_channel')?>" method="post" class="form-horizontal" role="form">
        <div class="input-group">
            <label>名称</label>
            <input name="name" type="text" class="form-control" size="16" value="" placeholder="请输入名称" />
        </div>
        <button type="submit" class="btn btn-success">确认提交</button>
    </form>
</div>

<div class="panel-container">
    <table class="table table-hover">
        <thead>
        <tr>
            <th class="left">名称</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($items as $item): ?>
            <tr>
                <td class="left"><?=$item->name?></td>
                <td>
                    <a class="btn btn-danger" data-type="post" href="<?=$this->url('./income/delete_channel', ['id' => $item->id])?>">删除</a>
                </td>
            </tr>
        <?php endforeach?>
        </tbody>
    </table>
    <?php if(empty($items)):?>
        <div class="page-empty-tip">
            空空如也~~
        </div>
    <?php endif;?>
</div>