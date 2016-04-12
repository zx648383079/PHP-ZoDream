<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
$page = $this->get('page');
?>
<button>增加充值类型</button>
<table>
    <thead>
    <tr>
         <th>ID</th> 
        <th>类型名称</th> 
        <th>金额(元)</th> 
        <th>点数</th> 
        <th>有效期(天)</th> 
        <th>操作</th> 
    </tr>
    </thead>
    <tbody>
        <?php foreach ($page->getPage() as $value) {?>
            <tr>
                <td><?php echo $value['id'];?></td>
                <td><?php echo $value['gname'];?></td>
                <td><?php echo $value['gmoney'];?></td>
                <td><?php echo $value['gfen'];?></td>
                <td><?php echo $value['gdate'];?></td>
                <td>[修改][删除]</td>
            </tr>
        <?php }?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="6"><?php $page->pageLink();?></th>
        </tr>
        <tr>
            <th colspan="6">前台充值地址：/EmpireCMS/e/member/buygroup/</th>
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