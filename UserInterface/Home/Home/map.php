<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = __('Map');
$apiKey = config('thirdparty.baidu.map');

$js = <<<JS
var builder = MapBuilder.useBaidu("map-target");
window.map_builder = builder;
JS;
if (!empty($x) && !empty($y)) {
	$js .= sprintf('builder.mark(%s, %s, %s);', $x, $y, var_export($marker, true));
}

$this->registerCssFile([
    '@zodream.min.css'
])->registerJsFile([
    '//api.map.baidu.com/api?v=1.0&type=webgl&ak='.$apiKey,
    '@jquery.min.js',
    '@map.min.js'
])->registerJs($js, View::JQUERY_READY);
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
	<div id="map-target" style="width: 100%;height: 100vh;"></div>
	<?=$this->footer()?>
</body>
</html>

