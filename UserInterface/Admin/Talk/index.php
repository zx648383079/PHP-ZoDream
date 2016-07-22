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
<div>
    
    <form method="POST">
        随想：<textarea rows="1" cols="40" placeholder="内容" name="content" required></textarea>
        <button type="submit">发表</button>
    </form>
</div>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>内容</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($page->getPage() as $value) {?>
            <tr>
                <form method="POST">
                    <td><?php echo $value['id'];?></td>
                    <td>
                        <input type="hidden" name="id" value="<?php echo $value['id'];?>">
                        <textarea rows="1" cols="40"  name="content"><?php echo $value['content'];?></textarea>
                    </td>
                    <td>
                        <button type="submit">修改</button>
                        
                        <a href="#" class="btn">删除</a>
                    </td>
                </form>
            </tr>
        <?php }?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3">
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