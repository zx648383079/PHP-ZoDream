<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\View\View */
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
?>
<a class="btn" href="<?php $this->url('user/addRole');?>">增加角色</a>
<table class="table table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>角色名称</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($this->gain('data', array()) as $value) {?>
            <tr>
                <td><?php echo $value['id'];?></td>
                <td><?php echo $value['name'];?></td>
                <td>[<a href="<?php $this->url('user/addRole/id/'.$value['id']);?>">编辑</a>][删除]</td>
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