<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = __('Security Verification');
$this->registerCssFile([
    '@zodream.min.css'
])->registerJsFile([
	'https://cdn.jsdelivr.net/npm/@fingerprintjs/fingerprintjs@5/dist/fp.min.js',
    '@jquery.min.js',
]);
?>

<!DOCTYPE html>
<html lang="<?=app()->getLocale()?>">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $this->title ?></title>

	<?=$this->header();?>
	<style>
		.loader {
			width: 3.125rem;
			padding: 0.5rem;
			aspect-ratio: 1;
			border-radius: 50%;
			background: #25b09b;
			--_m: 
				conic-gradient(#0000 10%,#000),
				linear-gradient(#000 0 0) content-box;
			-webkit-mask: var(--_m);
					mask: var(--_m);
			-webkit-mask-composite: source-out;
					mask-composite: subtract;
			animation: l3 1s infinite linear;
		}
		@keyframes l3 {to{transform: rotate(1turn)}}
	</style>
</head>
<body>
	<div class="safety-contianer">
		<h2><?= request()->host() ?></h2>
		<h3><?= __('检查站点链接是否安全') ?></h3>

		<div class="verify-control --with-waiting">
			<div class="control-body">
				<div class="loader"></div>
			</div>
			<div class="control-meta">
				<?= __('点击进行验证') ?>
			</div>
		</div>
	</div>

	<?=$this->footer()?>

	<script>
		async function generateDeviceId() {
			// 加载 FingerprintJS
			const fp = await FingerprintJS.load();
			const result = await fp.get();
			return result.visitorId;
		}
		$(document).ready(function() {
			$('.verify-control').click(function() {
				var $this = $(this);
				if (!$this.hasClass('--with-waiting')) {
					return;
				}
				var ele = $(this).removeClass('--with-waiting').find('.control-meta');
				ele.text('正在验证中，请稍侯...');
				generateDeviceId().then(function(device_id) {
					$.post('/seo/home/safety', {
						device_id: device_id
					}, function(res) {
						if (res.code == 200) {
							ele.text('验证通过！');
							location.reload();
							return;
						}
						ele.text(res.message);
					});
				});
				
			});
		});
	</script>
</body>
</html>

