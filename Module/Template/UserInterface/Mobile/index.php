<?php

use Zodream\Template\View;

/** @var $this View */
$this->registerCssFile('@font-awesome.min.css')
    ->registerCssFile('@template_mobile.min.css')
    ->registerJsFile('@jquery.min.js')
    ->registerJsFile('@jquery-ui.min.js')
    ->registerJsFile('@jquery.htmlClean.min.js')
    ->registerJsFile('@template_mobile.min.js');
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <title>可视化布局</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?=$this->header()?>
</head>
<body>
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
                <li data-size="375*667"><i class="fa fa-mobile"></i>iPhone 6</li>
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
    </ul>
</nav>
<div style="position: relative;">
    <div id="weight" class="left fixed">
        <div class="panel">
            <div class="head">
                <span class="title">部件</span>
                <a class="fa fa-close"></a>
            </div>
            <div class="body">
                <ul class="menu">
                    <li class="expand open">
                        <div class="head">
                            布局
                            <span class="fa fa-chevron-down"></span>
                        </div>
                        <div class="body list-view">
                            <div class="item grid">
                                <div class="preview ">
                                    <div class="thumb">
                                        <span class="fa fa-user"></span>
                                    </div>
                                    <p class="title">说明</p>
                                </div>
                                <div class="action">
                                    <a class="edit">编辑</a>
                                    <a class="drag">拖拽</a>
                                    <a class="del">删除</a>
                                </div>
                                <div class="view">
                                    <div class="row">
                                        <div class="column col-6">hhhhhhhhhhhhhhhhhhhhhh</div>
                                        <div class="column col-6">个v个v刚刚好</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="expand">
                        <div class="head">
                            布局
                            <span class="fa fa-chevron-down"></span>
                        </div>
                        <div class="body list-view">
                            <div class="item grid">
                                <div class="thumb">
                                    <span class="fa fa-user"></span>
                                </div>
                                <p class="title">说明</p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div id="mainMobile" style="width: 320px; height: 568px;">
        <div id="mainGrid">
            <div class="row">

            </div>
        </div>
    </div>
    <div id="property" class="right fixed">
        <div class="panel">
            <div class="head">
                <span class="title">属性</span>
                <a class="fa fa-close"></a>
            </div>
            <div class="body">

            </div>
        </div>
    </div>
</div>

<?=$this->footer()?>
</body>
</html>