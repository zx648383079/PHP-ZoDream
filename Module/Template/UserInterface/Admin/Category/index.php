
<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Theme;
/** @var $this View */
$this->title = '分类管理';
?>

<?= Theme::tooltip('分类列表') ?>
<div class="panel-container">
    <div class="page-search-bar">
        <a class="btn btn-success pull-right">新增分类</a>
    </div>

    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>分类名</th>
                <th>统计</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($items as $item):?>
            <tr>
                <td><?= $item['id'] ?></td>
                <td class="left"><?= Theme::treeLevel($item['level']) ?><?= $item['name'] ?></td>
                <td></td>
                <td>
                    <div class="btn-group  btn-group-xs">
                        <a class="btn btn-info">查看</a>
                        <a class="btn btn-light">编辑</a>
                        <a class="btn btn-danger">删除</a>
                    </div>
                </td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
    <?= Theme::emptyTooltip($items) ?>
</div>