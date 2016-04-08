<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
$page = $this->get('page');
?>

<form>
    搜索： 
    <input name="keyboard" type="text">
    <select name="show">
        <option value="1">反馈标题</option>
        <option value="2">反馈内容</option>
        <option value="3">空间主人用户ID</option>
        <option value="4">留言者IP</option>
    </select>
    <button type="submit">搜索</button>
</form>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>标题(点击查看)</th>
        <th>空间主人</th>
        <th>发布时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($page->getPage() as $value) {?>
            <tr>
                <td><?php echo $value['fid'];?></td>
                <td><?php echo $value['title'];?></td>
                <td><?php echo $value['fid'];?></td>
                <td><?php echo $value['addtime'];?></td>
                <td>[删除]</td>
            </tr>
        <?php }?>
    </tbody>
    <tfoot>
    <tr>
        <th colspan="5">
            <?php $page->pageLink();?>
            <button>批量删除</button>
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