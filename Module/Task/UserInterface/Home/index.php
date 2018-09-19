<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
?>
<div>
    <h1>任务管理平台</h1>
</div>

    <table class="table  table-bordered well">
        <thead>
        <tr>
            <th>任务</th>
            <th>时长</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item):?>
            <tr>
                <td><?=$item->name?></td>
                <td><?=$item->time?></td>
                <td>
                    <div class="btn-group  btn-group-xs">
                        <a href="">
                            <i class="fa"></i>
                        </a>
                       <a class="btn btn-default btn-xs" href="<?=$this->url('./task/edit', ['id' => $item->id])?>">编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./task/delete', ['id' => $item->id])?>">删除</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>