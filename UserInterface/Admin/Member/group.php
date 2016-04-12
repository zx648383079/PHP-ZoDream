<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
?>
<button>增加会员组</button>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>会员组名称</th>
        <th>级别值</th>
        <th>发送短消息</th>
        <th>注册地址</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($this->get('data', array()) as $value) {?>
            <tr>
                <td><?php echo $value['groupid'];?></td>
                <td><?php echo $value['groupname'];?></td>
                <td><?php echo $value['level'];?></td>
                <td>发送信息</td>
                <td>注册地址</td>
                <td>[修改][删除]</td>
            </tr>
        <?php }?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="6">说明：级别值越高，查看信息的权限越大</th>
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