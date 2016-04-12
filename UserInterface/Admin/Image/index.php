<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
?>
<div>
    增加图片信息类别:
    <form>
        类别名称: <input type="text" name="" value="">
        <button type="submit">增加</button>
        <button type="rest">重置</button>
    </form>
</div>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>类别名称</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($this->get('data', array()) as $value) {?>
            <tr>
                <td><?php echo $value['classid'];?></td>
                <td><input type="text" name="" value="<?php echo $value['classname'];?>"></td>
                <td>[修改][删除]</td>
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