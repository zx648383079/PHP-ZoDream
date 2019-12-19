<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream Chat';
$js = <<<JS
registerChat()
JS;
$this->registerJs($js);
?>

<button id="toggle-btn">切换悬浮/固定</button>

<div class="dialog-chat dialog-chat-page">

</div>
