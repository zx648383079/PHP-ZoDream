<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
$page = $this->get('page');
?>
<button>注册会员</button>
<button>前台会员列表</button>
<form>
    关键字:
    <select name="show">
    <option value="1">用户名</option>
    <option value="2">邮箱</option>
    </select>
    <input name="keyboard" type="text">
    <select name="groupid">
        <option value="0">不限级别</option>
    </select>
    <select name="schecked">
    <option value="0">不限</option>
    <option value="1">未审核</option>
    <option value="2">审核</option>
    </select>
    <input type="submit" name="Submit" value="搜索">
    &nbsp;&nbsp; [<a href="#">未审核会员</a>] [<a href="#">已审核会员</a>]
</form>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>用户名</th>
        <th>会员组</th>
        <th>注册时间</th>
        <th>记录</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($page->getPage() as $value) {?>
            <tr>
                <td><?php echo $value['userid'];?></td>
                <td><?php echo $value['username'];?></td>
                <td><?php echo $value['groupid'];?></td>
                <td>hh</td>
                <td>
                    [购买] [消费]
                </td>
                <td>[修改] [删除]</td>
            </tr>
        <?php }?>
    </tbody>
    <tfoot>
    <tr>
        <th colspan="6">
            <?php $page->pageLink();?>
            <button>审核</button>
            <button>删除</button>
            <input type="checkbox">全选
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