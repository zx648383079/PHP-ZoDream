<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
$label_list = [
    'un_pay' => $this->url('./@admin/order', ['status' => 10]),
    'shipped' => $this->url('./@admin/order', ['status' => 40]),
    'finish' => $this->url('./@admin/order', ['status' => 80]),
    'cancel' => $this->url('./@admin/order', ['status' => 1]),
    'invalid' => $this->url('./@admin/order', ['status' => 2]),
    'paid_un_ship' => $this->url('./@admin/order', ['status' => 20]),
    'received' => $this->url('./@admin/order', ['status' => 60]),
    'uncomment' => $this->url('./@admin/order', ['status' => 60]),
    'refunding' => $this->url('./@admin/order', ['status' => 81]),
    'legwork' => 'javascript:alert(\'请登录小程序查看\');',
    'bulletin' => $this->url('/auth/admin/bulletin'),
];
$js = <<<JS
bindCheck();
JS;
$this->registerJs($js);
?>

<?php foreach($subtotal as $item):?>
<a class="column-item" data-name="<?=$item['name']?>" href="<?=isset($label_list[$item['name']]) ? $label_list[$item['name']] : 'javascript:;'?>" target="_blank">
    <div class="icon">
        <div class="fa fa-file"></div>
    </div>
    <div class="content">
        <h3 class="name"><?=$item['label']?></h3>
        <p class="count"><?=intval($item['count'])?></p>
    </div>
</a>
<?php endforeach;?>