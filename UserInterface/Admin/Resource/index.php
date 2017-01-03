<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Support\Html;
use Zodream\Infrastructure\Factory;
use Zodream\Infrastructure\Disk\Directory;
/** @var $this \Zodream\Domain\View\View */
/** @var $file \Zodream\Infrastructure\Disk\Directory */
$children = $file->children();
$parent = $file->parent()->getRelative(Factory::root());
$this->extend('layout/header');
?>

<table class="table table-hover">
    <thead>
    <tr>
        <th>文件名</th>
        <th>类型</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php if ($parent !== false):?>
    <tr>
        <td><?=Html::a('..', [null, 'file' => $parent])?></td>
        <td></td>
        <td></td>
    </tr>
    <?php endif;?>
    <?php foreach ($children as $item) :
        $fullName = $item->getRelative(Factory::root());
        ?>
        <tr>
            <?php if ($item instanceof Directory):?>
                <td><?=Html::a($item->getName(), [null, 'file' => $fullName]);?></td>
                <td>文件夹</td>
                <td>重命名 删除</td>
            <?php else:?>
                <td><?=Html::a($item->getName(), '#');?></td>
                <td>文件</td>
                <td>
                    <?=Html::a('下载', ['download', 'file' => $fullName])?>
                    <?=Html::a('编辑', ['resource/add', 'file' => $fullName])?>
                    重命名 删除</td>
            <?php endif;?>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>

<?=$this->extend('layout/footer')?>
