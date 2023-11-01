<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = sprintf('“%s” 的表单列表', $model['name']);
?>
<div class="panel-container">
<div class="page-search-bar">
        <form class="form-horizontal" role="form">
            <div class="input-group">
                <label class="sr-only" for="keywords">标题</label>
                <input type="text" class="form-control" name="keywords" id="keywords" placeholder="标题" value="<?=$this->text($keywords)?>">
            </div>
            <button type="submit" class="btn btn-default">搜索</button>
            <input type="hidden" name="id" value="<?=$model->id?>">
        </form>
        <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/form/create', ['model_id' => $model->id])?>">新增数据</a>
    </div>

    <table class="table table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>标题</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item):?>
            <tr>
                <td><?=$item['id']?></td>
                <td class="left">
                    <?=$item['title']?>
                </td>
                <td>
                    <div class="btn-group toggle-icon-text">
                        <a class="btn btn-default btn-xs" href="<?=$this->url('./@admin/form/edit', ['id' => $item['id'], 'model_id' => $item['model_id']])?>" title="编辑详细信息">
                            <span>编辑</span>
                            <i class="fa fa-edit"></i>
                        </a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/form/delete', ['id' => $item['id'], 'model_id' => $item['model_id']])?>" title="删除此表单">
                            <span>删除</span>
                            <i class="fa fa-trash"></i>
                        </a>
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