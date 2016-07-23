<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\View\Engine\DreamEngine */
/** @var $page \Zodream\Domain\Html\Page */
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
$page = $this->gain('page');
?>
<button>管理错误报告分类</button>
限制显示： 
<select name="cid">
    <option value="0">显示所有分类</option>
</select>

<table>
    <thead>
    <tr>
         <th>信息标题</th> 
        <th>所属分类</th> 
        <th>发布者IP</th> 
        <th>发布时间</th> 
        <th></th> 
    </tr>
    </thead>
    <tbody>
        <?php foreach ($page->getPage() as $value) {?>
            <tr>
                <td><?php echo $value['title'];?></td>
                <td><?php echo $value['cid'];?></td>
                <td><?php echo $value['errorip'];?></td>
                <td><?php echo $value['errortime'];?></td>
                <td>[修改][删除]</td>
            </tr>
        <?php }?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2">
                <?php $page->pageLink();?>
                <button type="submit">批量删除</button>
                <input type="checkbox" name="" value="">全选
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