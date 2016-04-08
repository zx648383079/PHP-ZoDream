<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
$page = $this->get('page');
?>
<button>管理支付接口</button>
<button>支付参数设置</button>

<form>
    时间从 
    <input name="startday" type="text">
     到 
     <input name="endday" type="text">
     ，关键字： 
     <input name="keyboard" type="text">
    <select name="show">
        <option value="0">订单号</option>
        <option value="1">汇款者</option>
        <option value="2">汇款IP</option>
        <option value="3">备注</option>
    </select>
    <button type="submit">搜索</button>
</form>

<table>
    <thead>
    <tr>
        <th><input type="checkbox"></th> 
         <th>订单号</th> 
        <th>汇款者</th> 
        <th>金额</th> 
        <th>汇款时间</th> 
        <th>汇款IP</th> 
        <th>备注</th> 
        <th>接口</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($page->getPage() as $value) {?>
            <tr>
                <td><input type="checkbox" name="id[]" value="<?php echo $value['id'];?>"></td>
                <td><?php echo $value['orderid'];?></td>
                <td></td>
                <td><?php echo $value['money'];?></td>
                <td><?php echo $value['posttime'];?></td>
                <td><?php echo $value['payip'];?></td>
                <td><?php echo $value['paybz'];?></td>
                <td><?php echo $value['type'];?></td>
            </tr>
        <?php }?>
    </tbody>
    <tfoot>
        <tr>
            <th>
                <?php $page->pageLink();?>
                <button>批量删除</button>
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