<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\Bot\Domain\Model\EditorTemplateModel;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '编辑图文';
$js = <<<JS
    bindEditor('#container');
JS;
$this->registerJs($js);
?>
<div class="page-tooltip-bar">
    <p class="tooltip-header">操作提示</p>
    <ul>
        <li>编辑图文</li>
    </ul>
    <span class="tooltip-toggle"></span>
</div>

<?=Form::open($model, './@admin/media/save')?>
    <?=Form::text('title', true)?>
    <?=Form::select('parent_id', [$model_list, 'id', 'title', ['无']])?>
    <?=Form::text('thumb', true)?>
    <?=Form::radio('show_cover', ['不显示', '显示'])?>
    <?=Form::radio('open_comment', ['不打开', '打开'])?>
    <?=Form::radio('only_comment', ['所有人', '粉丝'])?>
    <div class="editor-box">
        <div class="editor-template-box">
            <ul class="template-menu">
                <?php foreach(EditorTemplateModel::$type_list as $key => $item):?>
                    <li data-url="<?=$this->url('./@admin/template', ['type' => $key])?>"><?=$item?></li>
                <?php endforeach;?>
            </ul>
            <div class="template-box">
                <ul class="templdate-list">
                    
                </ul>
            </div>
        </div>
        <div class="editor">
            <script id="container" style="bot_idth: 430px;height: 730px" name="content" type="text/plain">
                    <?=$model->content?>
            </script>
        </div>
        
    </div>
    <button class="btn btn-primary">保存</button>
    <input type="hidden" name="type" value="news">
<?= Form::close('id') ?>