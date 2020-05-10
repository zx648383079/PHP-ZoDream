<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
$data = [];
foreach ($map as $item) {
    $data[] = $item['url'];
}
$data[] = sprintf('生成成功！共 %d 条，<a href="/sitemap.xml" target="_blank">👉点击查看</a>', count($data));
$data = json_encode($data);
$js = <<<JS
renderCMD({$data});
JS;
$this->registerJs($js, View::JQUERY_READY);
?>

<div class="cmd-box">

    
</div>