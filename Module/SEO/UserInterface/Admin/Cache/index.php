<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '缓存管理';
?>

<a href="<?=$this->url('./@admin/cache/clear')?>" data-type="del" data-tip="确认清除所有缓存" class="btn btn-danger">清除缓存</a>