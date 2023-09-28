<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = __('error page');
if ($code === 401) {
	$this->layout = __DIR__. '/layouts/main.php';
}

$timeOut = ($time ?? 3) * 1000;
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
		<p style="margin-bottom: 2rem;"><?=__($message ?? 'error page')?></p>
		<p class="text-center">
			<?php if (isset($url)): ?>
                <a class="btn btn-primary no-jax" href="<?=$url?>"><?=__('go to')?></a>
            <script>
                setTimeout(function () {
                    window.location.href = '<?= $url ?>';
                }, <?= $timeOut ?>);
            </script>
			<?php elseif($code === 401):?>
				<a class="btn btn-primary no-jax" href="<?=$this->url(config('auth.home'), ['redirect_uri' => request()->url()])?>"><?=__('Sign in')?></a>
            <?php endif;?>
			<a class="btn btn-default no-jax" href="<?=$this->url('/')?>"><?=__('back home')?></a>
		</p>
	</div>
</div>
