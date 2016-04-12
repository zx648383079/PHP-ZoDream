<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
?>
<div>
    <form method="POST">
        分类： <input type="text" name="name" placeholder="分类" required>
        <button type="submit">增加</button>
    </form>
</div>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>分类</th>
        <th>缩略名</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($this->get('data', array()) as $value) {?>
            <tr>
                <form method="POST">
                    <td><?php echo $value['id'];?></td>
                    <td>
                        <input type="hidden" name="id" value="<?php echo $value['id'];?>">
                        <input type="text" name="name" value="<?php echo $value['name'];?>" placeholder="分类" required>
                    </td>
                    <td>
                        <?php echo $value['slug'];?>
                    </td>
                    <td>
                        <button type="submit">修改</button>
                        <a href="#" class="btn">删除</a>
                    </td>
                </form>
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