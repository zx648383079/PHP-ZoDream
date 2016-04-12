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
        名称： <input type="text" name="name" placeholder="名称" required>
        网址： <input type="url" name="url" required placeholder="网址">
        说明： <textarea name="description" rows="1" cols="50"></textarea>
        Logo： <input type="text" name="logo" value="" placeholder="LOGO">
        顺序： <input type="number" name="position" value="0">
        <button type="submit">增加</button>
    </form>
</div>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>名称</th> 
        <th>网址</th> 
        <th>说明</th> 
        <th>Logo</th> 
        <th>顺序</th> 
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
                        <input type="text" name="name" value="<?php echo $value['name'];?>" placeholder="名称" required>
                    </td>
                    <td>
                        <input type="url" name="url" required value="<?php echo $value['url'];?>" placeholder="网址">
                    </td>
                    
                    <td>
                        <textarea name="description" rows="1" cols="50"><?php echo $value['description'];?></textarea>
                    </td>
                    
                    <td>
                        <input type="text" name="logo" value="<?php echo $value['logo'];?>" placeholder="LOGO">
                    </td>
                    
                    <td>
                        <input type="number" name="position" value="<?php echo $value['position'];?>">
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