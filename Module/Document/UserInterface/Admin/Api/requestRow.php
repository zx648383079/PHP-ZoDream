<?php
defined('APP_DIR') or exit();
?>
<?php foreach($request_fields as $item):?>
<tr data-id="<?=$item['id']?>">
    <td><?=$item['name']?></td>
    <td><?=$item['title']?></td>
    <td><?=$item->type_list[$item->type]?></td>
    <td><?=$item['is_required'] ? '是' : '否'?></td>
    <td><?=$item['default_value']?></td>
    <td><?=$item['remark']?></td>
    <td style="width: 10%">
        <a href="javascript:;" data-action="edit" class="fa fa-edit"></a>
        <a href="javascript:;" data-action="delete" class="fa fa-trash"></a>
    </td>
</tr>
<?php endforeach;?>