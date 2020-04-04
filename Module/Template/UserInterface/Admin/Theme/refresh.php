<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '刷新主题';
?>

<a href="<?=$this->url('./@admin/theme/install')?>" class="btn" data-type="ajax">刷新</a>