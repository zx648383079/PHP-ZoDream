<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
$page = $this->get('page');
?>
<button>增加支付方式</button>

<table>
    <thead>
    <tr>
        <th>ID</th> 
        <th>支付方式</th> 
        <th>默认</th> 
        <th>开启</th> 
        <th>操作</th>  
    </tr>
    </thead>
    <tbody>
        <?php foreach ($page->getPage() as $value) {?>
            <tr>
                <td><?php echo $value['payid'];?></td>
                <td><?php echo $value['payname'];?></td>
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