<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\View\View */
/** @var $page \Zodream\Domain\Html\Page */
$this->extend('layout/head');
?>
<button>管理操作日志</button>
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
    栏目ID：
    <input name="classid" type="text">
    信息ID：
    <input name="id" type="text">
    <button type="submit">搜索</button>
</form>
<table class="table table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>操作事件</th>
        <th>数据</th>
        <th>操作网址</th>
        <th>操作IP</th>
        <th>操作用户</th>
        <th>操作时间</th>
        <th>删除</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($page->getPage() as $value) :?>
            <tr>
                <td><?=$value['id'];?></td>
                <td><?=$value['event'];?></td>
                <td><?=$value['data'];?></td>
                <td><?=$value['url'];?></td>
                <td><?=$value['ip'];?></td>
                <td><?=$value['user'];?></td>
                <td><?=$this->time($value['create_at']);?></td>
                <td>[删除 <input type="checkbox">]</td>
            </tr>
        <?php endforeach;?>
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


<?=$this->extend('layout/foot')?>