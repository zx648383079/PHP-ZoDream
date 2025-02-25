<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\MessageService\Domain\Repositories\MessageProtocol;
/** @var $this View */

$this->title = '模板管理';
?>

<div class="page-tooltip-bar">
    <p class="tooltip-header">操作提示</p>
    <ul>
        <li>一些特殊的必须保留</li>
    </ul>
    <span class="tooltip-toggle"></span>
</div>

<div class="panel-container">
    <div class="page-search-bar">
        <form class="form-horizontal" role="form">
            <div class="input-group">
                <label class="sr-only" for="keywords">关键字</label>
                <input type="text" class="form-control" name="keywords" id="keywords" placeholder="关键字">
            </div>
            <button type="submit" class="btn btn-default">搜索</button>
        </form>
        <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/template/edit')?>">新增模板</a>
    </div>

    <table class="table table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>唯一代码</th>
            <th>标题</th>
            <th>类型</th>
            <th>外部编号</th>
            <th>是否启用</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item):?>
            <tr>
                <td><?=$item->id?></td>
                <td class="left"><?=$item->name?></td>
                <td class="left"><?=$item->title?></td>
                <td><?=$item->type > MessageProtocol::TYPE_TEXT ? 'HTML' : 'TEXT'?></td>
                <td class="left"><?=$item->target_no?></td>
                <td>
                    <a data-type="toggle" href="<?=$this->url('./@admin/template/toggle', ['id' => $item['id']])?>?>" class="toggle-switch<?=$item->status > 0 ? ' toggled' : ''?>"></a>
                </td>
                <td>
                    <div class="btn-group toggle-icon-text">
                        <a class="btn btn-default no-jax" href="<?=$this->url('./@admin/template/edit', ['id' => $item['id']])?>"  title="编辑详细信息">
                            <span>编辑</span>
                            <i class="fa fa-edit"></i>
                        </a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/template/delete', ['id' => $item['id']])?>" title="删除此模板">
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