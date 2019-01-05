<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
$data = [];
foreach ($map as $item) {
    $data[] = $item['url'];
}
$data[] = '生成成功！';
$data = json_encode($data);
$js = <<<JS
renderCMD({$data});
JS;
$this->registerJs($js, View::JQUERY_READY);
?>

<div class="cmd-box">

</div>