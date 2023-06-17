<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = sprintf('“%s” 的内容列表', $cat['title']);
?>

<div class="panel-container">
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
        <a class="btn btn-success pull-right no-jax" href="<?=$this->url('./@admin/content/create', ['cat_id' => $cat->id, 'model_id' => $model->id, 'parent_id' => $parent_id])?>">新增文章</a>
    </div>
    
    <table class="table table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>标题</th>
            <th>分类</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item):?>
            <tr>
                <td><?=$item['id']?></td>
                <td class="text-left">
                    <a href="<?=$this->url('./content', ['category' => $item['cat_id'], 'model' => $model->id, 'id' => $item['id']])?>" target="_blank"><?=$this->text($item['title'])?></a>
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
                    <div class="btn-group  btn-group-xs">
                        <?php if($model->child_model > 0):?>
                        <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/content', ['parent_id' => $item['id'], 'cat_id' => $item['cat_id'], 'model_id' => $model->child_model])?>">分集</a>
                        <?php endif;?>
                        <a class="btn btn-default no-jax" href="<?=$this->url('./@admin/content/edit', ['id' => $item['id'], 'cat_id' => $item['cat_id'], 'model_id' => $model->id])?>">编辑</a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/content/delete', ['id' => $item['id'], 'cat_id' => $item['cat_id'], 'model_id' => $model->id])?>">删除</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
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

