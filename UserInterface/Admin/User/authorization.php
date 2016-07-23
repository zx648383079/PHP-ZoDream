<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\View\Engine\DreamEngine */
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
?>



<div>
    增加权限 <br>
    <form method="POST">
        权限名称:<input type="text" name="name"  required placeholder="权限">
        <button type="submit">增加</button>
        <button type="reset">重置</button>
    </form>
</div>
<table class="table table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>权限名称</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($this->gain('data', array()) as $item) {?>
            <tr>
                <form method="POST">
                    <td>
                        <?php echo $item['id'];?>
                    </td>
                    <td>
                        <input type="hidden" name="id" value="<?php echo $item['id'];?>">
                        <input type="text" name="name" value="<?php echo $item['name'];?>">
                    </td>
                    <td>
                        <button type="submit">修改</button>
                        <button type="reset">删除</button>
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