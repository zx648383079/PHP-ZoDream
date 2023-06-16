<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'error page';
$timeOut = ($time ?? 3) * 1000;
?>

<div class="page-not-found">
	<div class="content">
		<img src="<?=$this->asset('images/404.png')?>">
		<p><?=__($message ?? 'error page')?></p>
		<p class="text-center">
            <?php if (isset($url)): ?>
                <a class="btn btn-primary" href="<?=$url?>"><?=__('go to')?></a>
            <script>
                setTimeout(function () {
                    window.location.href = '<?= $url ?>';
                }, <?= $timeOut ?>)
            </script>
            <?php endif;?>
			<a class="btn btn-default" href="<?=$this->url('/')?>"><?=__('back home')?></a>
		</p>
	</div>
</div>

