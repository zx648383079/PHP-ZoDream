<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
$page = $this->get('page');
?>
<button>增加会员表单</button>
<button>管理会员字段</button>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>表单名称</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($page->getPage() as $value) {?>
            <tr>
                <td><?php echo $value['fid'];?></td>
                <td><?php echo $value['fname'];?></td>
                <td>[修改] [复制] [删除]</td>
            </tr>
        <?php }?>
    </tbody>
    <tfoot>
    <tr>
        <th colspan="3">
            <?php $page->pageLink();?>
        </th>
    </tr>
    </tfoot>
</table>

<?php
$this->extend(array(
        'layout' => array(
            'foot'
        ))
);
?>