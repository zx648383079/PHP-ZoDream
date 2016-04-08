<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
?>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>接口名称</th>
        <th>状态</th>
        <th>接口类型</th>
        <th>绑定人数</th>
        <th>登录次数</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($this->get('data', array()) as $value) {?>
            <tr>
                <td><?php echo $value['id'];?></td>
                <td><?php echo $value['appname'];?></td>
                <td><?php echo $value['isclose'];?></td>
                <td><?php echo $value['apptype'];?></td>
                <td></td>
                <td></td>
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