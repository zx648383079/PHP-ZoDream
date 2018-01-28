<?php
use Zodream\Template\View;
/** @var $this View */
$this->extend('layout/header');
?>

<div class="page-tip">
    <p class="blue">操作提示</p>
    <ul>
        <li>关注公众号时自动回复</li>
    </ul>
    <span class="toggle"></span>
</div>
<div>
    <a href="<?=$this->url('./reply/add')?>">添加</a>
</div>
<div>
    <table class="table table-hover">
        <thead>
            <tr>
                <td>ID</td>
                <td>事件</td>
                <td>关键字</td>
                <td>操作</td>
            </tr>
        </thead>
        <tbody>
        <?php foreach($reply_list as $item): ?>
            <tr>
                <td><?=$item->id?></td>
                <td>
                    <?=$item->event?>
                </td>
                <td>
                    <?=$item->keywords?></td>
                <td>
                    <a class="btn btn-default btn-xs" href="<?=$this->url('./reply/edit', ['id' => $item->id])?>">编辑</a>
                    <a class="btn btn-danger" data-type="del" href="<?=$this->url('./reply/delete', ['id' => $item->id])?>">删除</a>
                </td>
            </tr>
        <?php endforeach?>
        </tbody>
        <tfoot>
            <?=$reply_list->getLink()?>
        </tfoot>
    </table>
</div>

<?php
$this->extend('layout/footer');
?>