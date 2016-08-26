<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Html;
/** @var $this \Zodream\Domain\View\View */
/** @var $page \Zodream\Domain\Html\Page */
$this->extend('layout/head');
?>
<?=Html::a('新增', 'post/add', ['class' => 'btn'])?>
<?=Html::a('管理分类', 'post/term', ['class' => 'btn'])?>
<form method="GET">
    搜索： <input type="text" name="search" value="" placeholder="标题" required>
    <button type="submit">搜索</button>
</form>
<table class="table table-hover">
    <thead>
    <tr>
        <th></th>
        <th>标题</th> 
        <th>作者</th> 
        <th>分类目录</th> 
        <th>标签</th> 
        <th>评论</th> 
        <th>日期</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($page->getPage() as $value) :?>
            <tr>
                <td>
                    <input type="checkbox" name="id[]" value="<?=$value['id'];?>">
                </td>
                <td><?=$value['title'];?></td>
                <td><?=$value['user'];?></td>
                <td><?=$value['term'];?></td>
                <td><?php //echo $value['tag'];?></td>
                <td><?=$value['comment_count'];?></td>
                <td><?=$this->time($value['create_at']);?></td>
                <td>
                    [<?=Html::a('编辑', ['post/add', 'id' => $value['id']])?>]
                    [<?=Html::a('删除', ['post/delete', 'id' => $value['id']])?>]
                </td>
            </tr>
        <?php endforeach;?>
    </tbody>
    <tfoot>
    <tr>
        <th colspan="8">
            <?php $page->pageLink();?>
        </th>
    </tr>
    </tfoot>
</table>

<?=$this->extend('layout/foot')?>