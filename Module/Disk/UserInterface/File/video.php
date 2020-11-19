<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$js = <<<JS
var player = videojs('example-video');
player.play();
JS;
$this->registerJs($js);
$this->registerCssFile('@video-js.min.css')
    ->registerJsFile('@video.min.js')
    ->registerJsFile('@videojs-contrib-hls.min.js');
?>

<video id="example-video" width="600" height="300" class="video-js vjs-default-skin" controls>
  <source
     src="<?=$this->url('./file/m3u8', ['id' => $disk['id']])?>"
     type="application/x-mpegURL">
</video>