<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '数据备份管理';
?>

<a href="<?=$this->url('./@admin/sql/back_up')?>" data-type="ajax" class="btn">备份</a>
<a href="<?=$this->url('./@admin/sql/clear')?>" data-type="del" data-tip="确认清除所有备份" class="btn btn-danger">清除备份</a>