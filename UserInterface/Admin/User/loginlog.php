<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\Response\View */
/** @var $page \Zodream\Domain\Html\Page */
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
$page = $this->gain('page');
?>
<button>管理登陆日志</button>
<form>
    时间从 
    <input name="startday" type="text" value="">
    到 
    <input name="endday" type="text" value="">
    ，关键字： 
    <input name="keyboard" type="text" id="keyboard" value="">
    <select name="show">
    <option value="0">不限</option>
    <option value="1">用户名</option>
    <option value="2">登陆IP</option>
    </select>
    <select name="status">
    <option value="0">所有状态</option>
    <option value="1">登陆成功日志</option>
    <option value="2">登陆失败日志</option>
    </select>
    <button type="submit">搜索</button>
</form>
<table class="table table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>登陆用户</th>
        <th>状态</th>
        <th>登录方式</th>
        <th>登陆IP</th>
        <th>登陆时间</th>
        <th>删除</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($page->getPage() as $value) {?>
            <tr <?php  echo $value['status'] == 0 ? 'class="danger"' : '';?>>
                <td><?php echo $value['id'];?></td>
                <td><?php echo $value['user'];?></td>
                <td><?php $this->tag($value['status'], ['失败', '成功']);?></td>
                <td><?php $this->tag($value['mode'], ['未知', '后台']);?></td>
                <td><?php echo $value['ip'];?></td>
                <td><?php $this->time($value['create_at']);?></td>
                <td>[删除 <input type="checkbox">]</td>
            </tr>
        <?php }?>
    </tbody>
    <tfoot>
    <tr>
        <th colspan="6">
            <?php $page->pageLink();?>
            <button>批量删除</button>
            <input type="checkbox"  value="">选中全部
        </th>
    </tr>
    </tfoot>
</table>

<form>
    删除从 
    <input name="startday" type="text">
    到 
    <input name="endday" type="text">
    之间的日志
   <button type="submit">提交</button>
</form>

<?php
$this->extend(array(
        'layout' => array(
            'foot'
        ))
);
?>