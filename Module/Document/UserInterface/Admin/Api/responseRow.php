<?php
defined('APP_DIR') or exit();
use Module\Document\Domain\Model\FieldModel;
?>
<?php foreach($response_fields as $item):?>
<tr class="<?=$item['parent_id'] < 1 ? 'warning' : ''?>"  data-id="<?=$item['id']?>">

    <td style="text-align: left;padding-left: 50px;">
        <?php if($item['level'] > 0):?>
            <span>ￂ<?=str_repeat('ｰ', $item['level'] - 1)?></span>
        <?php endif;?>
        <?=$item['name']?></td>
    <td><?=$item['title']?></td>
    <td><?=FieldModel::$type_list[$item['type']]?></td>

    <td><?=$item['mock']?></td>

    <td><?=$item['remark']?></td>

    <td style="width: 10%">
        <?php if(in_array($item['type'], ['array', 'object'])):?>
        <a href="javascript:;" data-action="child" class="fa fa-fw fa-plus"></a>
        <?php endif;?>
        <a href="javascript:;" data-action="edit" class="fa fa-edit"></a>
        <a href="javascript:;" data-action="delete" class="fa fa-trash"></a>
   
    </td>
</tr>
<?php endforeach;?>