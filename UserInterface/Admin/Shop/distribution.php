<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
$page = $this->get('page');
?>
<button>增加配送方式</button>

<table>
    <thead>
    <tr>
        <th>ID</th> 
        <th>配送方式</th> 
        <th>价格</th> 
        <th>默认</th> 
        <th>启用</th> 
        <th>操作</th>  
    </tr>
    </thead>
    <tbody>
        <?php foreach ($page->getPage() as $value) {?>
            <tr>
                <td><?php echo $value['pid'];?></td>
                <td><?php echo $value['pname'];?></td>
                <td><?php echo $value['price'];?>元</td>
                <td><?php echo $value['isdefault'];?></td>
                <td><?php echo $value['isclose'];?></td>
                <td>[设为默认] [修改] [删除]</td>
            </tr>
        <?php }?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="5"><?php $page->pageLink();?></th>
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