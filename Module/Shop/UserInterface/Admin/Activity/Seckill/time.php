<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '秒杀时间列表';
?>
<?php if($id < 1):?>
<div class="search">
    <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/activity/seckill/create_time')?>">新增秒杀时间</a>
</div>
<?php endif;?>
<table class="table table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>标题</th>
        <th>开始时间</th>
        <th>结束时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($model_list as $item):?>
        <tr>
            <td><?=$item->id?></td>
            <td>
                <?=$item->title?>
            </td>
            <td>
            <?=$item->start_at?>
            </td>
            <td>
            <?=$item->end_at?>
            </td>
            
            <td>
                <?php if($id < 1):?>
                <div class="btn-group  btn-group-xs">
                    <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/activity/seckill/edit_time', ['id' => $item->id])?>">编辑</a>
                    <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/activity/seckill/delete_time', ['id' => $item->id])?>">删除</a>
                </div>
                <?php else:?>
                <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/activity/seckill/goods', ['act_id' => $id, 'time_id' => $item->id])?>">添加商品</a>
                <?php endif;?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>