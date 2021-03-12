<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'error page';
?>

<div class="page-not-found">
	<div class="content">
		<img src="<?=$this->asset('images/404.png')?>">
		<p><?=__(isset($message)? $message: 'error page')?></p>
		<p class="text-center">
			<a class="btn btn-show" href="<?=$this->url('/')?>"><?=__('back home')?></a>
		</p>
	</div>
</div>

