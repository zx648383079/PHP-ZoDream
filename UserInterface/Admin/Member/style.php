<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
$page = $this->get('page');
?>
<button>增加会员空间模板</button>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>模板名称</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($page->getPage() as $value) {?>
            <tr>
                <td><?php echo $value['styleid'];?></td>
                <td><?php echo $value['stylename'];?></td>
                <td>[设为默认] [修改] [删除]</td>
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