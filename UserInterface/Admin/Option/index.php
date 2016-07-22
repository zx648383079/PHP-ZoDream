<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\Response\View */
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
?>
<div>
    
    <form method="POST">
        名称： <input type="text" name="name" placeholder="名称" required>
        值： <textarea name="value" rows="1" cols="30" placeholder="值" required></textarea>
        自动加载： <input type="text" name="autoload" placeholder="自动加载" value="yes">
        <button type="submit">增加</button>
    </form>
</div>

<table>
    <thead>
    <tr>
        <th>名称</th>
        <th>值</th>
        <th>自动加载</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($this->gain('data', array()) as $value) {?>
            <tr>
                <form method="POST">
                    <td>
                        <input type="text" name="name" value="<?php echo $value['name'];?>" placeholder="名称" required>
                    </td>
                    <td>
                        <textarea name="value" rows="1" cols="30"><?php echo $value['value'];?></textarea>
                    </td>
                    <td>
                        <input type="text" name="autoload" placeholder="自动加载" value="<?php echo $value['autoload'];?>">
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