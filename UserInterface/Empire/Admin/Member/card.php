<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
$page = $this->get('page');
?>
<button>增加点卡</button>
<button>批量增加点卡</button>
<button>管理过期点卡</button>
<table>
    <thead>
    <tr>
        <th></th> 
         <th>ID</th> 
        <th>卡号</th> 
        <th>金额(元)</th> 
        <th>有效期</th> 
        <th>点数</th> 
        <th>操作</th> 
    </tr>
    </thead>
    <tbody>
        <?php foreach ($page->getPage() as $value) {?>
            <tr>
                <td><input type="checkbox" name="cardid[]" value="<?php echo $value['cardid'];?>"></td>
                <td><?php echo $value['cardid'];?></td>
                <td><?php echo $value['card_no'];?></td>
                <td><?php echo $value['money'];?></td>
                <td><?php echo $value['carddate'];?></td>
                <td><?php echo $value['cardfen'];?></td>
                <td>[修改][删除]</td>
            </tr>
        <?php }?>
    </tbody>
    <tfoot>
        <tr>
            <th><input type="checkbox"></th>
            <th colspan="6">
                <?php $page->pageLink();?>
                <button type="submit">删除选中</button>
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