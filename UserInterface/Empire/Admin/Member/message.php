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
        <option value="1">留言内容</option>
        <option value="2">回复内容</option>
        <option value="3">留言者</option>
        <option value="4">空间主人用户ID</option>
        <option value="5">留言者IP</option>
    </select>
    <button type="submit">搜索</button>
</form>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>表单名称</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($page->getPage() as $value) {?>
            <tr>
                <td><?php echo $value['fid'];?></td>
                <td><?php echo $value['fname'];?></td>
                <td>[修改] [复制] [删除]</td>
            </tr>
        <?php }?>
    </tbody>
    <tfoot>
    <tr>
        <th colspan="3">
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