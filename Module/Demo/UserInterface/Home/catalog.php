<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
function fileHtml($file) {
    if ($file['type'] > 0) {
        return '<li><div class="name"><i class="fa '.$file['icon'].'"></i>'.$file['name'].'</div></li>';
    }
    $html = '';
    foreach($file['children'] as $item) {
        $html .= fileHtml($item);
    }
    return '<li class="tree-parent"><div class="name"> <i class="fa '.$file['icon'].'"></i>'.$file['name'].'</div><ul>'.$html.'</ul></li>';
}
?>

<div class="header">目录结构</div>

<ul class="tree-box">
    <?php foreach($files as $item):?>
        <?=fileHtml($item)?>
    <?php endforeach;?>
</ul>
