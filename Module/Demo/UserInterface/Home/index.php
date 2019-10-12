<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'DEMO';
$this->registerJsFile('./assets/ts/demo.js');
?>

<div class="metro-grid">
    <a href="<?=$this->url('./preview')?>">
        日期选择
    </a>
    <a href="<?=$this->url('./preview', ['page' => '360'])?>">
        横向360
    </a>
</div>
<iframe src="<?=$this->url('/')?>" frameborder="0" style="width: 320px; height: 640px">
</iframe>
