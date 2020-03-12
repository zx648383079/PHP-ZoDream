<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Theme;
use Zodream\Helpers\Str;
/** @var $this View */
$this->title = '商品卡密列表';
$js = <<<JS
bindGoodsCard();
JS;
$this->registerJs($js);
?>
<div class="page-search">
    <form class="form-horizontal" role="form">
        <span>商品【
            <a href="<?=$this->url('./@admin/goods/edit', ['id' => $model->id])?>"><?=$model->name?></a>
            】</span>
        <div class="input-group">
            <label class="sr-only" for="keywords">卡密</label>
            <input type="text" class="form-control" name="keywords" id="keywords" placeholder="卡密" value="<?=$keywords?>">
        </div>
        <button type="submit" class="btn btn-default">搜索</button>
        <input type="hidden" name="id" value="<?=$model->id?>">
    </form>
    <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/goods/create_card', ['id' => $model->id])?>">生成卡密</a>
    <a class="btn pull-right" href="<?=$this->url('./@admin/goods/import_card', ['id' => $model->id])?>">导入</a>
    <a class="btn pull-right" href="<?=$this->url('./@admin/goods/export_card', ['id' => $model->id])?>">导出</a>
</div>

<table class="table table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>卡密</th>
        <th>是否使用</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($card_list as $item):?>
        <tr>
            <td><?=$item->id?></td>
            <td>
                <?=$item->card_no?>
            </td>
            <td>
                <?php if($item->order_id > 0):?>
                   已使用
                   [<a href="<?=$this->url('./@admin/order/info', ['id' => $item->order_id])?>">查看</a>]
                <?php endif;?>
            </td>
            <td>
                <div class="btn-group  btn-group-xs">
                    <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/goods/delete_card', ['id' => $item->id])?>">删除</a>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div align="center">
    <?=$card_list->getLink()?>
</div>