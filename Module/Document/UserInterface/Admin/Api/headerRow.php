<?php
defined('APP_DIR') or exit();
?>
<?php foreach($header_fields as $item):?>
<tr data-id="<?=$item['id']?>">
    <td style="width: 20%"><?=$item['name']?></td>
    <td style="width: 35%"><?=$item['default_value']?></td>
    <td style="width: 35%"><?=$item['remark']?></td>
    <td style="width: 10%">
        <a href="javascript:;" data-action="edit" class="fa fa-edit"></a>
        <a href="javascript:;" data-action="delete" class="fa fa-trash"></a>
    </td>
</tr>
<?php endforeach;?>