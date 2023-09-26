<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\CMS\Domain\Repositories\SiteRepository;
/** @var $this View */
$this->title = sprintf('“%s” 的内容列表', $cat['title']);
?>

<div class="panel-container page-multiple-table">
    <div class="page-search-bar">
        <?php if($parent_id > 0):?>
        <a class="btn btn-success" href="<?=$this->url('./@admin/content',
            ['cat_id' => $cat->id])?>">返回</a>
        <?php endif;?>
        <form class="form-horizontal" role="form">
            <div class="input-group">
                <label class="sr-only" for="keywords">标题</label>
                <input type="text" class="form-control" name="keywords" id="keywords" placeholder="标题" value="<?=$this->text($keywords)?>">
            </div>
            <button type="submit" class="btn btn-default">搜索</button>
            <input type="hidden" name="cat_id" value="<?=$cat->id?>">
            <input type="hidden" name="parent_id" value="<?=$parent_id?>">
            <input type="hidden" name="model_id" value="<?=$model->id?>">
        </form>
        <div class="btn-group pull-right">
            <a class="btn btn-success no-jax" href="<?=$this->url('./@admin/content/create', ['cat_id' => $cat->id, 'model_id' => $model->id, 'parent_id' => $parent_id])?>">新增文章</a>
            <a class="btn page-multiple-toggle">批量操作</a>
        </div>
        
    </div>
    
    <table class="table table-hover">
        <thead>
        <tr>
            <th class="page-multiple-th">
                <i class="checkbox"></i>
            </th>
            <th>ID</th>
            <th>标题</th>
            <th>分类</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item):?>
            <tr>
                <td class="page-multiple-td" data-id="<?=$item['id']?>">
                    <i class="checkbox"></i>
                </td>
                <td><?=$item['id']?></td>
                <td class="text-left">
                    <a href="<?=$currentSite->url('./content', ['category' => $item['cat_id'], 'model' => $model->id, 'id' => $item['id']])?>" target="_blank"><?=$this->text($item['title'])?></a>
                </td>
                <td>
                    <?php if ($cat):?>
                        <a href="<?=$this->url('./@admin/content', ['cat_id' => $cat->id, 'model_id' => $model->id])?>">
                            <?=$cat->title?>
                        </a>
                    <?php else:?>
                    [未分类]
                    <?php endif;?>
                </td>
                <td>
                    <?= SiteRepository::formatStatus($item['status']) ?>
                </td>
                <td>
                    <div class="btn-group toggle-icon-text">
                        <?php if($model->child_model > 0):?>
                        <a class="btn btn-default" href="<?=$this->url('./@admin/content', ['parent_id' => $item['id'], 'cat_id' => $item['cat_id'], 'model_id' => $model->child_model])?>" title="管理分集列表">
                            <span>分集</span>
                            <i class="fa fa-th-list"></i>
                        </a>
                        <?php endif;?>
                        <a class="btn btn-info" href="<?=$currentSite->url('./content', ['category' => $item['cat_id'], 'model' => $model->id, 'id' => $item['id']])?>" target="_blank"  title="预览查实际显示效果">
                            <span>预览</span>
                            <i class="fa fa-globe"></i>
                        </a>
                        <a class="btn btn-success" data-type="form" href="<?=$this->url('./@admin/content/dialog', ['id' => $item['id'], 'cat_id' => $item['cat_id'], 'model_id' => $model->id])?>" title="快速编辑发布" data-title="快速编辑属性">
                            <span>属性</span>
                            <i class="fa fa-cog"></i>
                        </a>
                        <a class="btn btn-default no-jax" href="<?=$this->url('./@admin/content/edit', ['id' => $item['id'], 'cat_id' => $item['cat_id'], 'model_id' => $model->id])?>" title="编辑详细信息">
                            <span>编辑</span>
                            <i class="fa fa-edit"></i>
                        </a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/content/delete', ['id' => $item['id'], 'cat_id' => $item['cat_id'], 'model_id' => $model->id])?>" title="删除此文章">
                            <span>删除</span>
                            <i class="fa fa-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot class="page-multiple-action">
            <tr>
                <td class="page-multiple-th">
                    <i class="checkbox"></i>
                </td>
                <td class="left" colspan="5">
                    <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/content/delete', ['id' => 0, 'cat_id' => $item['cat_id'], 'model_id' => $model->id], false)?>">删除选中项（<span class="page-multiple-count">0</span>）</a>
                </td>
            </tr>
        </tfoot>
    </table>
    <?php if($model_list->isEmpty()):?>
        <div class="page-empty-tip">
            空空如也~~
        </div>
    <?php endif;?>
    <div align="center">
        <?=$model_list->getLink()?>
    </div>
</div>

