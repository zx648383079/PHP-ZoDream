<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>
<?php foreach($model_list as $item):?>
    <tr data-id="<?=$item->id?>" data-goods="<?=$item->goods_id?>">
        <td><?=$item->id?></td>
        <td>
            <?=$item->goods->name?>
        </td>
        <td class="auto-hide">
            <?=$item->goods->price?>
        </td>
        <td>
            <input type="text" class="form-control" name="price" value="<?=$item->price?>">
        </td>
        <td>
            <input type="text" class="form-control" name="amount" value="<?=$item->amount?>">
        </td>
        <td>
            <input type="text" class="form-control" name="every_amount" value="<?=$item->every_amount?>">
        </td>
        <td>
            <div class="btn-group">
                <a class="btn btn-danger" href="<?=$this->url('./@admin/activity/seckill/deleteGoods', ['id' => $item->id])?>">删除</a>
            </div>
        </td>
    </tr>
<?php endforeach; ?>