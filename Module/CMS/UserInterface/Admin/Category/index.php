<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '栏目管理';
?>

<div class="panel-container">
    <div class="page-search-bar">
        <a class="btn btn-success pull-right no-jax" href="<?=$this->url('./@admin/category/create')?>">新增栏目</a>
    </div>

    <table class="table table-hover tree-table">
        <thead>
        <tr>
            <th class="tree-arrow-td"></th>
            <th>ID</th>
            <th>栏目名</th>
            <th>分组</th>
            <th>统计</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item):?>
            <tr class="tree-item <?= $item['level'] < 1 ? 'tree-level-open' : 'tree-next-level' ?>" data-level="<?=$item['level']?>">
                <td class="tree-arrow-td" style="padding-left: <?= $item['level'] * 10 ?>px">
                    <i class="tree-item-arrow" title="点击展开/隐藏子项"></i>
                </td>
                <td><?=$item['id']?></td>
                <td class="text-left">
                    <?php if($item['level'] > 0):?>
                    <span>ￂ<?=str_repeat('ｰ', $item['level'] - 1)?>
                    <?php endif;?>
                    <a href="<?=$currentSite->url('./category', ['id' => $item['id']])?>"><?=$item['title']?></a>
                </td>
                <td><?= empty($item['groups']) ? '-' : __($item['groups']) ?></td>
                <td><?=intval($item['content_count'])?></td>
                <td class="text-right">
                    <div class="btn-group toggle-icon-text">
                        <?php if($item['type'] < 1):?>
                        <a class="btn btn-primary" href="<?=$this->url('./@admin/content', ['cat_id' => $item['id']])?>" title="管理栏目下文章列表">
                            <span>文章</span>
                            <i class="fa fa-th-list"></i>
                        </a>
                        <?php endif;?>
                        <a class="btn btn-info" href="<?=$currentSite->url('./category', ['id' => $item['id']])?>" target="_blank" title="预览查实际显示效果">
                            <span>预览</span>
                            <i class="fa fa-globe"></i>
                        </a>
                        <a class="btn btn-default no-jax" href="<?=$this->url('./@admin/category/edit', ['id' => $item['id']])?>"  title="编辑详细信息">
                            <span>编辑</span>
                            <i class="fa fa-edit"></i>
                        </a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/category/delete', ['id' => $item['id']])?>" title="删除此栏目">
                            <span>删除</span>
                            <i class="fa fa-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php if(empty($model_list)):?>
    <div class="page-empty-tip">
        空空如也~~
    </div>
    <?php endif;?>
</div>