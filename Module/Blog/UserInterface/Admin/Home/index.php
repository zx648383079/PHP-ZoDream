<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '欢迎使用博客管理平台';
?>

<h2>欢迎使用博客管理平台 ！</h2>

<?php foreach($subtotal as $item):?>
<div class="column-item">
    <div class="icon">
        <div class="fa <?=$item['icon']?>"></div>
    </div>
    <div class="content">
        <h3><?=$item['name']?></h3>
        <p><?=$item['count']?></p>
    </div>
</div>
<?php endforeach;?>

