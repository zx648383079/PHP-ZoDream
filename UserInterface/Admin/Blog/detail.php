<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Support\Html;
use Zodream\Domain\View\View;
/** @var $this View */
$js = <<<JS
    var ue = UE.getEditor('container');
    
JS;

$this->extend('layout/header')
    ->registerJsFile('/assets/ueditor/ueditor.config.js')
    ->registerJsFile('/assets/ueditor/ueditor.all.js')
    ->registerJs($js, View::JQUERY_READY);
?>
       <div class="page-header fixed">
            <ul class="path">
                <li>
                    <a>首页</a>
                </li>
                <li>
                    博客
                </li>
                <li class="active">
                    添加
                </li>
            </ul>
            <div class="title">
                 添加
            </div>
       </div>

       <form class="form-table ajax-form" action="<?=$this->url(['blog/update'])?>" method="POST" style="margin-top: 50px;">
           <div class="input-group">
                <label>标题</label>
                <div>
                    <input type="text" name="title" value="<?=$blog->title?>" required>
                    <input type="hidden" name="id" value="<?=$blog->id?>">
                </div>
            </div>
               <div class="input-group">
                    <label>简介</label>
                    <div>
                        <textarea name="description"><?=$blog->description?></textarea>
                    </div>
                </div>
                <div class="input-group">
                    <label>分类</label>
                    <div>
                        <select name="term_id" required>
                            <option>请选择</option>
                            <?php foreach ($term_list as $term):?>
                                <option value="<?=$term->id?>"
                                <?php if ($term->id == $blog->term_id): ?>
                                  selected
                                <?php endif;?>><?=$term->name?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="input-group">
                    <label>正文</label>
                    <div>
                        <script id="container" style="height: 400px" name="content" type="text/plain" required>
                            <?=$blog->content?>
                        </script>
                    </div>
                </div>
           <div class="actions fixed">
               <button class="btn">保存</button>
               <button class="btn" type="reset">重置</button>
           </div>
       </form>

<?php $this->extend('layout/footer'); ?>