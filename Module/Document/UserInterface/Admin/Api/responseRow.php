<?php
defined('APP_DIR') or exit();
?>
<?php foreach($response_fields as $item):?>
<tr class="<?=$item['parent_id'] < 1 ? 'warning' : ''?>"  data-id="<?=$item['id']?>">

    <td style="text-align: left;padding-left: 50px;"><?=$item['parent_id'] ? '└' : ''?><?=$item['name']?></td>
    <td><?=$item['title']?></td>
    <td><?=$item->type_list[$item->type]?></td>

    <td><?=$item['mock']?></td>

    <td><?=$item['remark']?></td>

    <td style="width: 10%">
        <?php if(in_array($item->type, ['array', 'object'])):?>
        <a href="javascript:;" data-action="child" class="fa fa-fw fa-plus"></a>
        <?php endif;?>
        <a href="javascript:;" data-action="edit" class="fa fa-edit"></a>
        <a href="javascript:;" data-action="delete" class="fa fa-trash"></a>
   
    </td>
</tr>
<?php endforeach;?>