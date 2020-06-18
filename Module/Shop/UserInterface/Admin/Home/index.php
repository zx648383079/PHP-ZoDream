<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
$label_list = [
    'un_pay' => [
        '待支付',
        $this->url('./@admin/order', ['status' => 10]),
    ],
    'shipped' => [
        '待签收',
        $this->url('./@admin/order', ['status' => 40]),
    ],
    'finish' => [
        '已完成',
        $this->url('./@admin/order', ['status' => 80]),
    ],
    'cancel' => [
        '已取消',
        $this->url('./@admin/order', ['status' => 1]),
    ],
    'invalid' => ['已失效', $this->url('./@admin/order', ['status' => 2]),],
    'paid_un_ship' => ['待发货', $this->url('./@admin/order', ['status' => 20]),],
    'received' => ['待评价', $this->url('./@admin/order', ['status' => 60]),],
    'uncomment' => ['未评价', $this->url('./@admin/order', ['status' => 60]),],
    'refunding' => ['退款中', $this->url('./@admin/order', ['status' => 81]),],
    'legwork' => ['待接单', 'javascript:alert(\'请登录小程序查看\');'],
    'bulletin' => [
        '未读消息',
        $this->url('/auth/admin/bulletin'),
    ],
];
$js = <<<JS
bindCheck();
JS;
$this->registerJs($js);
?>

<?php foreach($subtotal as $key => $item):?>
<a class="column-item" data-name="<?=$key?>" href="<?=isset($label_list[$key][1]) ? $label_list[$key][1] : 'javascript:;'?>" target="_blank">
    <div class="icon">
        <div class="fa fa-file"></div>
    </div>
    <div class="content">
        <h3 class="name"><?=$label_list[$key][0]?></h3>
        <p class="count"><?=intval($item)?></p>
    </div>
</a>
<?php endforeach;?>