<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Html;
/** @var $this \Zodream\Domain\Response\View */
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
$file = $this->get('file');
$data = $this->get('data', array());
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
    <?php if (!empty($file)):?>
    <tr>
        <td><?=Html::a('..', [null, 'file' => $file])?></td>
        <td></td>
        <td></td>
    </tr>
    <?php endif;?>
    <?php foreach ($data['dirs'] as $item) {?>
        <tr>
            <td><?=Html::a($item['name'], [null, 'file' => $item['full']]);?></td>
            <td>文件夹</td>
            <td>下载 重命名 删除</td>
        </tr>
    <?php }?>
    <?php foreach ($data['files'] as $item) {?>
        <tr>
            <td><?=Html::a($item['name'], ['#', 'file' => $item['full']]);?></td>
            <td>文件</td>
            <td>
                <?=Html::a('下载', ['download', 'file' => $item['full']])?>
                <?=Html::a('编辑', ['resource/add', 'file' => $item['full']])?>
                 重命名 删除</td>
        </tr>
    <?php }?>
    </tbody>
</table>

<?php
$this->extend(array(
    'layout' => array(
        'foot'
    ))
);
?>
