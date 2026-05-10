<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = __('Security Verification');
$this->registerCssFile([
    '@font-awesome.min.css',
    '@zodream.min.css',
    '@home.min.css'
])->registerJsFile([
    '@jquery.min.js',
    '@main.min.js',
    '@home.min.js'
]);
?>

<!DOCTYPE html>
<html lang="<?=app()->getLocale()?>">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $this->title ?></title>

	<?=$this->header();?>
</head>
<body>
	<div class="safety-contianer">
		<h3><?= __('检查站点链接是否安全') ?></h3>
		
	</div>

	<?=$this->footer()?>
</body>
</html>

