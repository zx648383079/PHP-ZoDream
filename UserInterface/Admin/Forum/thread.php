<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\View\View */
/** @var $page \Zodream\Domain\Html\Page */
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
$page = $this->gain('page');
?>

<table class="table table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>标题</th>
        <th>作者</th>
        <th>发布时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($page->getPage() as $value) {?>
            <tr>
                <td><?php echo $value['id'];?></td>
                <td><?php echo $value['title'];?></td>
                <td><?php echo $value['user_name'];?></td>
                <td><?php $this->time($value['create_at']);?></td>
                <td>[删除]</td>
            </tr>
        <?php }?>
    </tbody>
    <tfoot>
    <tr>
        <th colspan="5">
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