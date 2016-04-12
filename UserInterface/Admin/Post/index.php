<?php
defined('APP_DIR') or exit();
$this->extend(array(
    'layout' => array(
        'head'
    ))
);
$page = $this->get('page');
?>
<a class="btn" href="<?php $this->url('post/add');?>">新增</a>
<a class="btn" href="<?php $this->url('post/term');?>">管理分类</a>
<form method="GET">
    搜索： <input type="text" name="name" value="" placeholder="用户名" required>
    <button type="submit">搜索</button>
</form>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>用户名</th>
        <th>邮箱</th>
        <th>登录次数</th>
        <th>最后登录IP</th>
        <th>最后登陆时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($page->getPage() as $value) {?>
            <tr>
                <td><?php echo $value['id'];?></td>
                <td><?php echo $value['name'];?></td>
                <td><?php echo $value['email'];?></td>
                <td><?php echo $value['login_num'];?></td>
                <td><?php echo $value['update_ip'];?></td>
                <td><?php $this->time($value['update_at']);?></td>
                <td>[<a href="<?php $this->url('user/addUser/id/'.$value['id']);?>">编辑</a>][删除]</td>
            </tr>
        <?php }?>
    </tbody>
    <tfoot>
    <tr>
        <th colspan="8">
            <?php $page->pageLink();?>
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