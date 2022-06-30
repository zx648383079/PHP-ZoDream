<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = __('error page');
?>

<div class="container page-not-found">
	<div class="content">
		<div class="error-tag">
			<span class="tag-cover"></span>
			<span class="tag-icon"><?=$code ?? 404?></span>
		</div>
		<p><?=__($message ?? 'error page')?></p>
		<p class="text-center">
			<a class="btn btn-show" href="<?=$this->url('/')?>"><?=__('back home')?></a>
		</p>
	</div>
</div>
