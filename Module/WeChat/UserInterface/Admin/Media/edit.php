<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '编辑图文';
$js = <<<JS
    var ue = UE.getEditor('container');
JS;
$this->registerJsFile('/assets/ueditor/ueditor.config.js')
    ->registerJsFile('/assets/ueditor/ueditor.all.js')
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
                    <li class="active">素材</li>
                    <li>行业模板</li>
                    <li>节日模板</li>
                </ul>
                <div class="template-box">
                    <ul class="templdate-list">
                        <?php foreach($template_list as $item):?>
                           <li>
                                <?=$item->content?>
                           </li>
                        <?php endforeach;?>
                    </ul>
                </div>
            </div>
            <div class="editor">
                <script id="container" style="width: 500px;height: 800px" name="content" type="text/plain" required>
                        <?=$model->content?>
                </script>
            </div>
            
        </div>
        <button class="btn btn-primary">保存</button>
        <input type="hidden" name="id" value="<?=$model->id?>">
    </form>
</div>