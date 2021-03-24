<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream Tool';
$maps = [
    [
        'name' => 'css2scss',
        'tip' => 'css 转 scss',
        'icon' => 'fa-code',
        'url' => $this->url('./home/css'),
    ]
]
?>
<?php foreach($maps as $item):?>
<a class="column-item" href="<?= $item['url'] ?>">
    <div class="icon">
        <div class="fa <?=$item['icon']?>"></div>
    </div>
    <div class="content">
        <h3><?=$item['name']?></h3>
        <p><?=$item['tip']?></p>
    </div>
</a>
<?php endforeach;?>
