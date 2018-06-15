<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '上传日志文件';
?>
<h1><?=$this->title?></h1>
<form action="<?=$this->url('./log/save')?>" method="post" class="form-table" role="form" enctype="multipart/form-data">
    
    <div class="input-group">
        <label>名称</label>
        <input name="name" type="text" class="form-control"  placeholder="输入名称">
    </div>
    
    <div class="input-group">
        <label>文件</label>
        <input type="file" name="file[]" multiple>
    </div>

    <button type="submit" class="btn btn-success">确认</button>
</form>