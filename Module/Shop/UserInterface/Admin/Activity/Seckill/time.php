<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '秒杀时间列表';
?>
<?php if($is_edit):?>
<div class="search">
    <a class="btn btn-success pull-right" href="<?=$this->url('./admin/activity/seckill/create_time')?>">新增秒杀时间</a>
</div>
<?php endif;?>
<table class="table table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th class="auto-hide">开始时间</th>
        <th class="auto-hide">结束时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($model_list as $item):?>
        <tr>
            <td><?=$item->id?></td>
            <td>
                <?=$item->name?>
            </td>
            <td class="auto-hide">
                
            </td>
            <td class="auto-hide">
                
            </td>
            
            <td>
                <?php if($is_edit):?>
                <div class="btn-group  btn-group-xs">
                    <a class="btn btn-default btn-xs" href="<?=$this->url('./admin/activity/seckill/edit_time', ['id' => $item->id])?>">编辑</a>
                    <a class="btn btn-danger" data-type="del" href="<?=$this->url('./admin/activity/seckill/delete_time', ['id' => $item->id])?>">删除</a>
                </div>
                <?php else:?>
                <a class="btn btn-default btn-xs" href="<?=$this->url('./admin/activity/seckill/goods', ['act_id' => $act_id, 'time_id' => $item->id])?>">添加商品</a>
                <?php endif;?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div align="center">
    <?=$model_list->getLink()?>
</div>