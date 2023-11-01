<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '同步小说';
$url = $this->url('./', false);
$js = <<<JS
bindImport('{$url}');
JS;
$this->registerJs($js);
?>

<div class="panel-container">
    <div class="page-search-bar">
        <form class="form-horizontal" role="form">
            <div class="input-group">
                <label class="sr-only" for="keywords">标题</label>
                <input type="text" class="form-control" name="keywords" id="keywords" placeholder="标题">
            </div>
            <button type="submit" class="btn btn-default">搜索</button>
        </form>
    </div>

    <div class="book-box">
    </div>
</div>
<div class="dialog dialog-progress">
        <div class="dialog-header">同步进度</div>
        <div class="dialog-body">
            <progress value="0" max="100"></progress>
            <span>0/0</span>
        </div>
    </div>