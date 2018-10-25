<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\WeChat\Domain\Model\TemplateModel;
/** @var $this View */
$this->title = '编辑图文';
$js = <<<JS
    bindEditor('#container');
JS;
$this->registerJsFile('editor/medium-editor.min.js')
    ->registerCssFile('editor/medium-editor.min.css')
    ->registerCssFile('editor/default.min.css')
    ->registerJs($js);
?>
<div class="page-tip">
    <p class="blue">操作提示</p>
    <ul>
        <li>编辑图文</li>
    </ul>
    <span class="toggle"></span>
</div>

<div>
    <form class="form-inline" data-type="ajax" action="<?=$this->url('./admin/media/save')?>" method="post">
        <div class="input-group">
            <label for="name">标题</label>
            <input type="text" id="name" name="title" placeholder="标题" required value="<?=$model->title?>" size="100">
        </div>
        <div class="editor-box">
            <div class="editor-template-box">
                <ul class="template-menu">
                    <?php foreach(TemplateModel::$type_list as $key => $item):?>
                        <li data-url="<?=$this->url('./admin/template', ['type' => $key])?>"><?=$item?></li>
                    <?php endforeach;?>
                </ul>
                <div class="template-box">
                    <ul class="templdate-list">
                       
                    </ul>
                </div>
            </div>
            <div class="editor">
                <div id="container"><?=$model->content?></div>
            </div>
            
        </div>
        <button class="btn btn-primary">保存</button>
        <input type="hidden" name="id" value="<?=$model->id?>">
    </form>
</div>