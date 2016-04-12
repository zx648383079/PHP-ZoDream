<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
?>
<button>管理支付记录</button>
<button>支付参数设置</button>

<table>
    <thead>
    <tr>
        <th>接口名称</th> 
        <th>接口描述</th> 
        <th>状态</th> 
        <th>接口类型</th> 
        <th>操作</th> 
    </tr>
    </thead>
    <tbody>
        <?php foreach ($this->get('data', array()) as $value) {?>
            <tr>
                <td><?php echo $value['payname'];?></td>
                <td><?php echo $value['paysay'];?></td>
                <td><?php echo $value['isclose'];?></td>
                <td><?php echo $value['paytype'];?></td>
                <td>配置接口</td>
            </tr>
        <?php }?>
    </tbody>
</table>
<?php
$this->extend(array(
        'layout' => array(
            'foot'
        ))
);
?>