<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = __('error page');
$this->registerJs(<<<JS
$.pjax.disable();
JS
);
?>

<div class="page-not-found">
	<div class="content">
		<div class="error-tag">
			<span class="tag-cover"></span>
			<span class="tag-icon"><?=$code ?? 404?></span>
		</div>
		<p><?=__($message ?? 'error page')?></p>
		<p class="text-center">
			<?php if($code === 401):?>
			<a class="btn btn-primary" href="<?=$this->url(config('auth.home'), ['redirect_uri' => request()->url()])?>"><?=__('Sign in')?></a>
			<?php endif;?>
			<a class="btn btn-default" href="<?=$this->url('/')?>"><?=__('back home')?></a>
		</p>
	</div>
</div>
