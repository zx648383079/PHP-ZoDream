<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '短链接';
$this->registerCssFile([
    '@font-awesome.min.css',
    '@dialog.css',
    '@zodream.css',
    '@short.css'
])->registerJsFile([
    '@jquery.min.js',
    '@jquery.dialog.min.js',
    '@jquery.lazyload.min.js',
    '@main.min.js',
    '@short.min.js'
])->registerJs(sprintf('var BASE_URI = "%s";', $this->url('./', false)), View::HTML_HEAD);
?>

<div class="container short-box">
    <form action="<?=$this->url('./home/create')?>" method="post">
        <input type="text" name="source_url" placeholder="请输入长网址">
        <button class="btn btn-primary">生成</button>
    </form>

    <div class="short-code">
        <p>短链接生成成功！</p>
        <pre><code></code></pre>
    </div>
</div>