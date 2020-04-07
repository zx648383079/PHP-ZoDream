<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\Template\Domain\Page;
use Zodream\Html\Dark\Theme;
/** @var $this View */
/** @var $page Page */
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
                            <?=Theme::text('title', '', '标题')?>
                            <?=Theme::radio('settings[lazy]', ['关闭', '开启'], 0, '懒加载')?>
                        </div>
                        <div class="tab-item">
                        <div class="expand-box open">
                                <div class="expand-header">整体<span class="fa fa-chevron-down"></span></div>
                                <div class="expand-body">
                                    <div class="input-group">
                                        <label>外边距</label>
                                        <div class="side-input">
                                            <input type="text" id="title" class="form-control " name="settings[style][margin][]" size="4" placeholder="上">
                                            <input type="text" id="title" class="form-control " name="settings[style][margin][]" size="4" placeholder="右">
                                            <input type="text" id="title" class="form-control " name="settings[style][margin][]" size="4" placeholder="下">
                                            <input type="text" id="title" class="form-control " name="settings[style][margin][]" size="4" placeholder="左">
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <label>悬浮</label>
                                        <div class="">
                                            <select name="settings[style][position]">
                                                <option value="static">无</option>
                                                <option value="relative">相对定位</option>
                                                <option value="absolute">绝对定位</option>
                                                <option value="fixed">固定定位</option>
                                            </select>
                                            <div class="side-input">
                                                <input type="text" id="title" class="form-control " name="settings[style][top]" size="4" placeholder="上">
                                                <input type="text" id="title" class="form-control " name="settings[style][right]" size="4" placeholder="右">
                                                <input type="text" id="title" class="form-control " name="settings[style][bottom]" size="4" placeholder="下">
                                                <input type="text" id="title" class="form-control " name="settings[style][left]" size="4" placeholder="左">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <label for="settings_lazy">边框</label>
                                        <div class="">
                                            <input type="text" class="form-control" name="settings[style][border][value][]" placeholder="粗细" size="4">
                                            <select name="settings[style][border][value][]">
                                                <option value="">实线</option>
                                                <option value="">虚线</option>
                                            </select>
                                            <input type="color" name="settings[style][border][value][]">
                                            <div class="side-input">
                                                <span class="check-label">
                                                    <input type="checkbox" id="settings_style_border_side_0" name="settings[style][border][side][]" value="0" >
                                                    <label for="settings_style_border_side_0">上</label>
                                                </span><span class="check-label">
                                                    <input type="checkbox" id="settings_style_border_side_1" name="settings[style][border][side][]" value="1">
                                                    <label for="settings_style_border_side_1">右</label>
                                                </span><span class="check-label">
                                                    <input type="checkbox" id="settings_style_border_side_2" name="settings[style][border][side][]" value="2">
                                                    <label for="settings_style_border_side_2">下</label>
                                                </span><span class="check-label">
                                                    <input type="checkbox" id="settings_style_border_side_3" name="settings[style][border][side][]" value="3">
                                                    <label for="settings_style_border_side_3">左</label>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <label for="settings_style_border-radius_">圆角</label>
                                        <div class="">
                                            <input type="text" id="settings_style_border-radius_" class="form-control " name="settings[style][border-radius][]" value="" size="4" placeholder="左上">
                                            <input type="text" id="settings_style_border-radius_" class="form-control " name="settings[style][border-radius][]" value="" size="4" placeholder="右上">
                                            <br/>
                                            <input type="text" id="settings_style_border-radius_" class="form-control " name="settings[style][border-radius][]" value="" size="4" placeholder="左下">
                                            <input type="text" id="settings_style_border-radius_" class="form-control " name="settings[style][border-radius][]" value="" size="4" placeholder="右下">
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <label for="">字体颜色</label>
                                        <div>
                                            <input type="color" name="settings[style][color]">
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <label for="settings_style_background_value">背景</label>
                                        <div class="">
                                            <span class="radio-label">
                                                <input type="radio" id="settings_style_background_type0" name="settings[style][background][type]" value="0" checked="">
                                                <label for="settings_style_background_type0">图片</label>
                                            </span><span class="radio-label">
                                                <input type="radio" id="settings_style_background_type1" name="settings[style][background][type]" value="1">
                                                <label for="settings_style_background_type1">颜色</label>
                                            </span>
                                            <div class="file-input">
                                                <input type="text" id="settings_style_background_value" class="form-control " name="settings[style][background][value]" value="" size="20">
                                                <button type="button" data-type="upload">上传</button>
                                            </div>
                                            <input type="color" name="settings[style][background][value]" id="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="expand-box">
                                <div class="expand-header">标题<span class="fa fa-chevron-down"></span></div>
                                <div class="expand-body">
                                    <?=Theme::radio('settings[style][title][visibility]', ['显示', '隐藏'], 0, '可见')?>
                                    <div class="input-group">
                                        <label>内边距</label>
                                        <div class="side-input">
                                            <input type="text" id="title" class="form-control " name="settings[style][title][padding][]" size="4" placeholder="上">
                                            <input type="text" id="title" class="form-control " name="settings[style][title][padding][]" size="4" placeholder="右">
                                            <input type="text" id="title" class="form-control " name="settings[style][title][padding][]" size="4" placeholder="下">
                                            <input type="text" id="title" class="form-control " name="settings[style][title][padding][]" size="4" placeholder="左">
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <label for="settings_lazy">边框</label>
                                        <div class="">
                                            <input type="text" class="form-control" name="settings[style][title][border][value][]" placeholder="粗细"size="4">
                                            <select name="settings[style][title][border][value][]">
                                                <option value="">实线</option>
                                                <option value="">虚线</option>
                                            </select>
                                            <input type="color" name="settings[style][title][border][value][]">
                                            <div class="side-input">
                                                <span class="check-label">
                                                    <input type="checkbox" id="settings_style_border_side_0" name="settings[style][title][border][side][]" value="0" >
                                                    <label for="settings_style_border_side_0">上</label>
                                                </span><span class="check-label">
                                                    <input type="checkbox" id="settings_style_border_side_1" name="settings[style][title][border][side][]" value="1">
                                                    <label for="settings_style_border_side_1">右</label>
                                                </span><span class="check-label">
                                                    <input type="checkbox" id="settings_style_border_side_2" name="settings[style][title][border][side][]" value="2">
                                                    <label for="settings_style_border_side_2">下</label>
                                                </span><span class="check-label">
                                                    <input type="checkbox" id="settings_style_border_side_3" name="settings[style][title][border][side][]" value="3">
                                                    <label for="settings_style_border_side_3">左</label>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <label for="settings_style_border-radius_">圆角</label>
                                        <div class="">
                                            <input type="text" id="settings_style_border-radius_" class="form-control " name="settings[style][title][border-radius][]" value="" size="4" placeholder="左上">
                                            <input type="text" id="settings_style_border-radius_" class="form-control " name="settings[style][title][border-radius][]" value="" size="4" placeholder="右上">
                                            <br/>
                                            <input type="text" id="settings_style_border-radius_" class="form-control " name="settings[style][title][border-radius][]" value="" size="4" placeholder="左下">
                                            <input type="text" id="settings_style_border-radius_" class="form-control " name="settings[style][title][border-radius][]" value="" size="4" placeholder="右下">
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <label for="">字体颜色</label>
                                        <div>
                                            <input type="color" name="settings[style][title][color]">
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <label for="settings_style_background_value">背景</label>
                                        <div class="">
                                            <span class="radio-label">
                                                <input type="radio" id="settings_style_background_type0" name="settings[style][title][background][type]" value="0" checked="">
                                                <label for="settings_style_background_type0">图片</label>
                                            </span><span class="radio-label">
                                                <input type="radio" id="settings_style_background_type1" name="settings[style][title][background][type]" value="1">
                                                <label for="settings_style_background_type1">颜色</label>
                                            </span>
                                            <div class="file-input">
                                                <input type="text" id="settings_style_background_value" class="form-control " name="settings[style][title][background][value]" value="" size="20">
                                                <button type="button" data-type="upload">上传</button>
                                            </div>
                                            <input type="color" name="settings[style][title][background][value]" id="">
                                        </div>
                                    </div>
                                    <?=Theme::text('settings[style][title][font-size]', '', '字体大小')->size(4)?>
                                    <?=Theme::text('settings[style][title][font-weight]', '', '字体粗细')->size(4)?>
                                    <?=Theme::radio('settings[style][title][text-align]', ['居左', '居中', '居右'], 0, '字体粗细')?>
                                </div>
                            </div>
                            <div class="expand-box">
                                <div class="expand-header">内容<span class="fa fa-chevron-down"></span></div>
                                <div class="expand-body">
                                    <?=Theme::radio('settings[style][content][visibility]', ['显示', '隐藏'], 0, '可见')?>
                                    <div class="input-group">
                                        <label>内边距</label>
                                        <div class="side-input">
                                            <input type="text" id="title" class="form-control " name="settings[style][content][padding][]" size="4" placeholder="上">
                                            <input type="text" id="title" class="form-control " name="settings[style][content][padding][]" size="4" placeholder="右">
                                            <input type="text" id="title" class="form-control " name="settings[style][content][padding][]" size="4" placeholder="下">
                                            <input type="text" id="title" class="form-control " name="settings[style][content][padding][]" size="4" placeholder="左">
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <label for="settings_lazy">边框</label>
                                        <div class="">
                                            <input type="text" class="form-control" name="settings[style][content][border][value][]" placeholder="粗细" size="4">
                                            <select name="settings[style][content][border][value][]">
                                                <option value="">实线</option>
                                                <option value="">虚线</option>
                                            </select>
                                            <input type="color" name="settings[style][content][border][value][]">
                                            <div class="side-input">
                                                <span class="check-label">
                                                    <input type="checkbox" id="settings_style_border_side_0" name="settings[style][content][border][side][]" value="0" >
                                                    <label for="settings_style_border_side_0">上</label>
                                                </span><span class="check-label">
                                                    <input type="checkbox" id="settings_style_border_side_1" name="settings[style][content][border][side][]" value="1">
                                                    <label for="settings_style_border_side_1">右</label>
                                                </span><span class="check-label">
                                                    <input type="checkbox" id="settings_style_border_side_2" name="settings[style][content][border][side][]" value="2">
                                                    <label for="settings_style_border_side_2">下</label>
                                                </span><span class="check-label">
                                                    <input type="checkbox" id="settings_style_border_side_3" name="settings[style][content][border][side][]" value="3">
                                                    <label for="settings_style_border_side_3">左</label>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <label for="settings_style_border-radius_">圆角</label>
                                        <div class="">
                                            <input type="text" id="settings_style_border-radius_" class="form-control " name="settings[style][content][border-radius][]" value="" size="4" placeholder="左上">
                                            <input type="text" id="settings_style_border-radius_" class="form-control " name="settings[style][content][border-radius][]" value="" size="4" placeholder="右上">
                                            <br/>
                                            <input type="text" id="settings_style_border-radius_" class="form-control " name="settings[style][content][border-radius][]" value="" size="4" placeholder="左下">
                                            <input type="text" id="settings_style_border-radius_" class="form-control " name="settings[style][content][border-radius][]" value="" size="4" placeholder="右下">
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <label for="">字体颜色</label>
                                        <div>
                                            <input type="color" name="settings[style][content][color]">
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <label for="settings_style_background_value">背景</label>
                                        <div class="">
                                            <span class="radio-label">
                                                <input type="radio" id="settings_style_background_type0" name="settings[style][content][background][type]" value="0" checked="">
                                                <label for="settings_style_background_type0">图片</label>
                                            </span><span class="radio-label">
                                                <input type="radio" id="settings_style_background_type1" name="settings[style][content][background][type]" value="1">
                                                <label for="settings_style_background_type1">颜色</label>
                                            </span>
                                            <div class="file-input">
                                                <input type="text" id="settings_style_background_value" class="form-control " name="settings[style][content][background][value]" value="">
                                                <button type="button" data-type="upload">上传</button>
                                            </div>
                                            <input type="color" name="settings[style][content][background][value]" id="">
                                        </div>
                                    </div>
                                    <?=Theme::text('settings[style][content][font-size]', '', '字体大小')->size(4)?>
                                    <?=Theme::text('settings[style][content][font-weight]', '', '字体粗细')->size(4)?>
                                    <?=Theme::radio('settings[style][content][text-align]', ['居左', '居中', '居右'], 0, '字体粗细')?>
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
    <form class="dialog-body" action="<?=$this->url('./@admin/weight/save')?>" method="post">
        <p><input type="text" name="title" placeholder="请输入标题"></p>
        <textarea id="editor-container" style="height: 400px;" name="content" placeholder="请输入内容"></textarea>
    </form>
    <div class="dialog-footer">
        <button type="button" class="dialog-yes">确认</button>
        <button type="button" class="dialog-close">取消</button>
    </div>
</div>
