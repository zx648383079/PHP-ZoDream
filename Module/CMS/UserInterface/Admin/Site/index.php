<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '站点列表';
?>

<div class="page-tooltip-bar">
    <p class="tooltip-header">操作提示</p>
    <ul>
        <li>站点管理，绑定访问站点域名、路径</li>
        <li>可以更改不同主题，但请注意：更改主题会导致栏目、文章、表单数据清空</li>
        <li>管理不同站点的栏目、文章、表单，只需点击列表中具体站点“操作 -> 管理”即可，显示“管理中”则表示当前管理的站点</li>
    </ul>
    <span class="tooltip-toggle"></span>
</div>

<div class="panel-container">
    <div class="page-search-bar">
        <a class="btn btn-success pull-right" href="<?=$this->url('./@admin/site/create')?>">新增站点</a>
    </div>

    <table class="table table-hover">
        <thead>
        <tr>
            <th>站点名</th>
            <th>语言</th>
            <th>网址</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($model_list as $item): ?>
            <tr>
                <td class="left">
                    <?php if($item->is_default):?>
                       <strong>[默认]</strong>
                    <?php endif;?>
                    <?=$item->title?>
                </td>
                <td><?=$item['language']?></td>
                <td class="left">[<?=$item->match_type < 1 ? '域名' : '路径'?>]<?=$item->match_rule ?: '(空)'?></td>
                <td class="right">
                    <?php if(empty($currentSite) || $item->id != $currentSite['id']):?>
                            <a href="<?=$this->url('./@admin/site/change', ['id' => $item->id])?>" data-type="ajax" class="no-jax">管理</a>
                        <?php else:?>
                            <a class="active" href="javascript:;">管理中</a>
                        <?php endif;?>
                    <div class="btn-group toggle-icon-text">
                        <?php if(!$item->is_default):?>
                            <a class="btn btn-success" data-type="ajax" href="<?=$this->url('./@admin/site/default', ['id' => $item->id])?>" title="设置此站点为前后台默认显示站点">
                                <span>设为默认</span>
                                <i class="fa fa-anchor"></i>
                            </a>
                        <?php endif;?>
                        <a class="btn btn-info" href="<?= $item->preview_url ?>" target="_blank" title="预览查实际显示效果">
                            <span>预览</span>
                            <i class="fa fa-globe"></i>
                        </a>
                        <a class="btn btn-primary" href="<?=$this->url('./@admin/site/option', ['id' => $item->id])?>" title="配置站点特殊信息">
                            <span>配置</span>
                            <i class="fa fa-cog"></i>
                        </a>
                        <a class="btn btn-default" href="<?=$this->url('./@admin/site/edit', ['id' => $item->id])?>" title="编辑详细信息">
                            <span>编辑</span>
                            <i class="fa fa-edit"></i>
                        </a>
                        <a class="btn btn-danger" data-type="del" href="<?=$this->url('./@admin/site/delete', ['id' => $item->id])?>" title="删除此站点">
                            <span>删除</span>
                            <i class="fa fa-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
        <?php endforeach?>
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


