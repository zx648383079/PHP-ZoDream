<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\Template\Domain\Page;

$base_url = $this->url('./admin/');
$id = $model->id;
$js = <<<JS
bindPage('{$id}', '{$base_url}');
JS;


/** @var $this View */
/** @var $page Page */
$this->registerCssFile('@template.css')
    ->registerJsFile('@jquery-ui.min.js')
    ->registerJsFile('@jquery.htmlClean.min.js')
    ->registerJsFile('@template.min.js')
    ->registerJs($js, View::JQUERY_READY);
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
            <a data-type="ajax" href="<?=$this->url('./admin/weight/install')?>">
                <i class="fa fa-sync"></i>
                <span>刷新</span>
            </a>
        </li>
    </ul>
</nav>
<div id="page-box">
    <div id="weight" class="left fixed">
        <div class="panel">
            <div class="head">
                <span class="title">部件</span>
                <a class="fa fa-close"></a>
            </div>
            <div class="body">
            <?php foreach ($weight_list as $key => $weights):?>
                <ul class="menu">
                    <li class="expand open">
                        <div class="head">
                            布局
                            <span class="fa fa-chevron-down"></span>
                        </div>
                        <div class="body list-view">
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
    </div>
    <div id="mainMobile" class="<?= $model->type > 0 ? 'mobile-320':''?>">
        <iframe id="mainGrid" src="<?=$this->url('./admin/page/template', ['id' => $model->id, 'edit' => true])?>">
            
        </iframe>
    </div>
    <div id="property" class="right fixed">
        <div class="panel">
            <div class="head">
                <span class="title">属性</span>
                <a class="fa fa-close"></a>
            </div>
            <div class="body">
            <div class="zd-tab">
                    <div class="zd-tab-head"><div class="zd-tab-item active">
                            普通
                        </div><div class="zd-tab-item">
                            高级
                        </div><div class="zd-tab-item">
                            样式
                        </div></div>
                    <div class="zd-tab-body">
                        <div class="zd-tab-item active">
                            <div class="input-group">
                                <label for="">标题</label>
                                <input type="text">
                            </div>
                            
                        </div>
                        <div class="zd-tab-item">
                            <div class="panel">
                                <div class="panel-header">标题</div>
                                <div class="panel-body">
                                    <div class="input-group">
                                        <label for="">背景</label>
                                        <input type="text">
                                    </div>
                                    <div class="input-group">
                                        <input type="radio" name="" id="">显示
                                        <input type="radio" name="" id="">不显示
                                    </div>
                                    <div class="input-group">
                                        <label for="">边框圆角</label>
                                        <input type="text">
                                    </div>
                                    <div class="input-group">
                                        <label for="">颜色</label>
                                        <input type="text">
                                    </div>
                                    <div class="input-group">
                                        <label for="">字体</label>
                                        <input type="text">
                                    </div>
                                    <div class="input-group">
                                        <label for="">位置</label>
                                        <select name="" id="">
                                            <option value="">居左</option>
                                            <option value="">居中</option>
                                            <option value="">居右</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="panel">
                                <div class="panel-header">边框</div>
                                <div class="panel-body">
                                    <div class="input-group">
                                        <label for="">背景</label>
                                        <input type="text">
                                    </div>
                                    <div class="input-group">
                                        <label for="">边框</label>
                                        <input type="text">
                                    </div>
                                    <div class="input-group">
                                        <label for="">边框圆角</label>
                                        <input type="text">
                                    </div>
                                    <div class="input-group">
                                        <label for="">外边距</label>
                                        <input type="text">
                                    </div>
                                    <div class="input-group">
                                        <label for="">内边距</label>
                                        <input type="text">
                                    </div>
                                    <div class="input-group">
                                        <label for="">粗细</label>
                                        <input type="text">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="zd-tab-item">
                            <?php foreach($style_list as $item):?>
                            <div class="style-item">
                                <img src="<?=$item['thumb']?>" alt="<?=$item['title']?>">
                            </div>
                            <?php endforeach;?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
