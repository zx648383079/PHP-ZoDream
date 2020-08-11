<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '分享到微博客';
$js = <<<JS
bindSharePage();
JS;
$this->registerCssFile([
    '@dialog.css',
    '@animate.min.css',
    '@micro.css'])
    ->registerJsFile([
        '@jquery.dialog.min.js',
        '@jquery.upload.min.js',
        '@main.min.js',
        '@micro.min.js'
    ])
    ->registerJs(sprintf('var BASE_URI = "%s";', $this->url('./', false)), View::HTML_HEAD)
    ->registerJs($js, View::JQUERY_READY);
?>

<div class="micro-skin">
    <div class="micro-publish">
        <div class="title">分享到微博客
            <div class="tip">
                已输入<em>0</em>字
            </div>
        </div>
        <form action="<?=$this->url('./share/save', false)?>" method="post">
            <div class="input">
                <textarea name="content" required></textarea>
            </div>
            <div class="input-row">
                <input type="text" name="title" value="<?=$this->text($title)?>">
            </div>
            <div class="input-row">
                <input type="text" name="url" value="<?=$this->text($url)?>" disabled>
            </div>
            <div class="input-row">
                <textarea name="summary"><?=$this->text($summary)?></textarea>
            </div>
            <div class="pic-box">
                <?php foreach($pics as $item):?>
                   <div class="img-item">
                       <img src="<?=$this->text($item)?>" alt="图片">
                       <i class="fa fa-times"></i>
                       <input type="hidden" name="pics[]" value="<?=$this->text($item)?>">
                   </div>
                <?php endforeach;?>
            </div>
            <div class="actions">
                <select>
                    <option value="">公开</option>
                </select>
                <button class="btn">分享</button>
            </div>
            <input type="hidden" name="appid" value="<?=$this->text($appid)?>">
            <input type="hidden" name="sharesource" value="<?=$this->text($sharesource)?>">
        </form>
    </div>
</div>