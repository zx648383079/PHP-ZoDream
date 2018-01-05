<?php

use Zodream\Template\View;

/** @var $this View */
$this->registerCssFile('@font-awesome.min.css')
    ->registerCssFile('@zodream.min.css')
    ->registerCssFile('@template.min.css')
    ->registerJsFile('@jquery.min.js')
    ->registerJsFile('@template.js');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>模板编辑</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?=$this->header()?>
</head>

<body class="template-edit-page">
    <div class="template-page-header">
        <div class="action_list">
            <button class="btn">保存</button>
            <button class="btn">取消</button>
        </div>
    </div>
    <div class="template-page-body">
        <div id="weight-box" class="zd-panel template-weight-left">
            <div class="zd-panel-head">
                <span class="title">部件</span>
                <a class="fa fa-close"></a>
            </div>
            <div class="zd-panel-body">
                <div class="zd-tab">
                    <div class="zd-tab-head">
                        <div class="zd-tab-item active">
                            布局
                        </div>
                        <div class="zd-tab-item">
                            基本
                        </div>
                        <div class="zd-tab-item">
                            高级
                        </div>
                    </div>
                    <div class="zd-tab-body">
                        <div class="zd-tab-item active">
                            <div class="zd-list-view">
                                <div class="zd-list-item">
                                    <div class="preview">
                                        <img src="image/banner1.jpg" alt="">
                                        <p>两栏式</p>
                                    </div>
                                    <div class="action">
                                        <i class="fa fa-edit"></i>
                                        <i class="fa fa-gear"></i>
                                        <i class="fa fa-remove"></i>
                                    </div>
                                    <div class="view">
                                        <div class="row">
                                            <div class="col-md-6 weight-list">
                                            </div>
                                            <div class="col-md-6 weight-list">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="zd-list-item">
                                    <div class="preview">
                                        <img src="image/banner2.jpg" alt="">
                                        <p>三栏</p>
                                    </div>
                                    <div class="action">
                                        <i class="fa fa-edit"></i>
                                        <i class="fa fa-gear"></i>
                                        <i class="fa fa-remove"></i>
                                    </div>
                                    <div class="view">
                                        <div class="row">
                                            <div class="col-md-4 weight-list">
                                            </div>
                                            <div class="col-md-4 weight-list">
                                            </div>
                                            <div class="col-md-4 weight-list">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="zd-list-item">
                                    <div class="preview">
                                        <img src="image/banner3.jpeg" alt="">
                                        <p>四栏</p>
                                    </div>
                                    <div class="action">
                                        <i class="fa fa-edit"></i>
                                        <i class="fa fa-gear"></i>
                                        <i class="fa fa-remove"></i>
                                    </div>
                                    <div class="view">
                                        <div class="row">
                                            <div class="col-md-3 weight-list">
                                            </div>
                                            <div class="col-md-3 weight-list">
                                            </div>
                                            <div class="col-md-3 weight-list">
                                            </div>
                                            <div class="col-md-3 weight-list">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="zd-tab-item">
                            <div class="zd-list-view">
                                <div class="zd-list-item">
                                    <div class="preview">
                                        <img src="image/banner1.jpg" alt="">
                                        <p>导航</p>
                                    </div>
                                    <div class="action">
                                        <i class="fa fa-edit"></i>
                                        <i class="fa fa-gear"></i>
                                        <i class="fa fa-remove"></i>
                                    </div>
                                    <div class="view">
                                        <div>
                                            1111111111111111111
                                        </div>
                                    </div>
                                </div>
                                <div class="zd-list-item">
                                    <div class="preview">
                                        <img src="image/banner1.jpg" alt="">
                                        <p>导航</p>
                                    </div>
                                    <div class="action">
                                        <i class="fa fa-edit"></i>
                                        <i class="fa fa-gear"></i>
                                        <i class="fa fa-remove"></i>
                                    </div>
                                    <div class="view">
                                        <div>
                                            333333333
                                        </div>
                                    </div>
                                </div>
                                <div class="zd-list-item">
                                    <div class="preview">
                                        <img src="image/banner1.jpg" alt="">
                                        <p>导航</p>
                                    </div>
                                    <div class="action">
                                        <i class="fa fa-edit"></i>
                                        <i class="fa fa-gear"></i>
                                        <i class="fa fa-remove"></i>
                                    </div>
                                    <div class="view">
                                        <div>
                                            55555
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="zd-tab-item">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="template-weight-box">

        </div>
        <div id="property-box" class="zd-panel right template-weight-property">
            <div class="zd-panel-head">
                <span class="title">属性</span>
                <a class="fa fa-close"></a>
            </div>
            <div class="zd-panel-body">
                <div class="zd-tab">
                    <div class="zd-tab-head">
                        <div class="zd-tab-item active">
                            普通
                        </div>
                        <div class="zd-tab-item">
                            高级
                        </div>
                        <div class="zd-tab-item">
                            样式
                        </div>
                    </div>
                    <div class="zd-tab-body">
                        <div class="zd-tab-item active">
                        </div>
                        <div class="zd-tab-item">
                        </div>
                        <div class="zd-tab-item">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?=$this->footer()?>
</body>
</html>