<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\Template\Domain\Page;
use Zodream\Html\Dark\Theme;
/** @var $this View */
/** @var $page Page */

$this->title = $model->title;

$id = $model->id;
$js = <<<JS
bindPage('{$id}');
JS;
$this->registerJsFile([
    'ueditor/ueditor.config.js',
    'ueditor/ueditor.all.js',
])->registerJs($js, View::JQUERY_READY);
?>

<nav class="top-nav">
    <ul class="navbar">
        <li>
            <div>
                <i class="fa fa-mobile"></i>
                <span>屏幕</span>
            </div>
            <ul class="down mobile-size">
                <li data-size="320*568"><i class="fa fa-mobile"></i>iPhone 5</li>
                <li data-size="360*640"><i class="fa fa-mobile"></i>Galaxy S5</li>
                <li data-size="370*640"><i class="fa fa-mobile"></i>Lumia 650</li>
                <li data-size="375*812"><i class="fa fa-mobile"></i>iPhone X</li>
                <li data-size="412*732"><i class="fa fa-mobile"></i>Nexus 5X</li>
                <li data-size="414*736"><i class="fa fa-mobile"></i>iPhone 6 Plus</li>
                <li data-size="768*1024"><i class="fa fa-tablet"></i>iPad</li>
                <li data-size="*"><i class="fa fa-desktop"></i>全屏</li>
            </ul>
        </li>
        <li>
            <div class="mobile-rotate">
                <i class="fa fa-undo"></i>
                <span>旋转</span>
            </div>
        </li>
        <li>
            <a data-type="ajax" href="<?=$this->url('./@admin/weight/install')?>">
                <i class="fa fa-sync"></i>
                <span>刷新</span>
            </a>
        </li>
    </ul>
</nav>
<div id="page-box">
    <div class="panel-group">
        <div class="panel-item" data-panel="weight">
            <div class="panel-header">
                <span class="title">部件</span>
                <a class="fa fa-close"></a>
            </div>
            <div class="panel-body">
            <?php foreach ($weight_list as $key => $weights):?>
                <ul class="menu">
                    <li class="expand-box open">
                        <div class="expand-header">
                            布局
                            <span class="fa fa-chevron-down"></span>
                        </div>
                        <div class="expand-body list-view">
                            <?php foreach ($weights as $item):?>
                            <div class="weight-edit-grid" data-type="weight" data-weight="<?=$item->id?>">
                                <div class="weight-preview">
                                    <div class="thumb">
                                        <span class="fa fa-user"></span>
                                    </div>
                                    <p class="title"><?=$item->name?></p>
                                </div>
                                <div class="weight-action">
                                    <a class="refresh">刷新</a>
                                    <?php if ($item->editable):?>
                                    <a class="edit">编辑</a>
                                    <?php endif;?>
                                    <a class="property">属性</a>
                                    <a class="drag">拖拽</a>
                                    <a class="del">删除</a>
                                </div>
                                <div class="weight-view">
                                    <img src="/assets/images/ajax.gif" alt="">
                                </div>
                            </div>
                            <?php endforeach;?>
                        </div>
                    </li>
                </ul>
                <?php endforeach;?>
            </div>
        </div>
        <div class="panel-item min" data-panel="property">
            <div class="panel-header">
                <span class="title">属性</span>
                <a class="fa fa-close"></a>
            </div>
            <div class="panel-body">
            <div class="tab-box">
                    <div class="tab-header"><div class="tab-item active">
                            普通
                        </div><div class="tab-item">
                            高级
                        </div><div class="tab-item">
                            样式
                        </div></div>
                    <div class="tab-body form-table">
                        <div class="tab-item active">
                        </div>
                        <div class="tab-item">
                        <div class="expand-box open">
                                <div class="expand-header">整体<span class="fa fa-chevron-down"></span></div>
                                <div class="expand-body">
                                    
                                </div>
                            </div>
                            <div class="expand-box">
                                <div class="expand-header">标题<span class="fa fa-chevron-down"></span></div>
                                <div class="expand-body">
                                    
                                </div>
                            </div>
                            <div class="expand-box">
                                <div class="expand-header">内容<span class="fa fa-chevron-down"></span></div>
                                <div class="expand-body">
                                    
                                </div>
                            </div>
                        </div>
                        <div class="tab-item">
                            <div class="style-item" data-id="0">
                                无
                            </div>
                            <?php foreach($style_list as $item):?>
                            <div class="style-item" data-id="<?=$item->id?>">
                                <img src="<?=$this->url('./@admin/theme/asset', ['folder' => $theme->path, 'file' => $item->thumb], false)?>" alt="<?=$item['name']?>">
                            </div>
                            <?php endforeach;?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="mainMobile" class="<?= $model->type > 0 ? 'mobile-320':''?>">
        <iframe id="mainGrid" src="<?=$this->url('./@admin/page/template', ['id' => $model->id, 'edit' => true])?>">
            
        </iframe>
        <canvas class="top-rule"></canvas>
            <canvas class="left-rule"></canvas>
            <!-- <div class="rule-tools">
                <i class="fa fa-plus-circle"></i>
                <i class="fa fa-minus-circle"></i>
                <i class="fa fa-expand-arrows-alt"></i>
                <i class="fa fa-expand"></i>
                <i class="fa fa-undo"></i>
            </div>
            <div class="rule-lines">
            </div> -->
    </div>
</div>

<div id="edit-dialog" class="dialog dialog-box" data-type="dialog" >
    <div class="dialog-header">
        <div class="dialog-title">编辑</div>
        <i class="fa fa-close dialog-close"></i>
    </div>
    <form class="dialog-body form-table custom-config-view" action="<?=$this->url('./@admin/weight/save')?>" method="post">
        
    </form>
    <div class="dialog-footer">
        <button type="button" class="dialog-yes">确认</button>
        <button type="button" class="dialog-close">取消</button>
    </div>
</div>
