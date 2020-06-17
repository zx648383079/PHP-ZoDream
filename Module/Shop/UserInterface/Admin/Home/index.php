<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
$label_list = [
    'un_pay' => '待支付',
    'shipped' => '待签收',
    'finish' => '已完成',
    'cancel' => '已取消',
    'invalid' => '已失效',
    'paid_un_ship' => '待发货',
    'received' => '待评价',
    'uncomment' => '未评价',
    'refunding' => '退款中',
    'legwork' => '待接单'
];
$js = <<<JS
bindCheck();
JS;
$this->registerJs($js);
?>

<?php foreach($subtotal as $key => $item):?>
<div class="column-item" data-name="<?=$key?>">
    <div class="icon">
        <div class="fa fa-file"></div>
    </div>
    <div class="content">
        <h3><?=$label_list[$key]?></h3>
        <p class="count"><?=intval($item)?></p>
    </div>
</div>
<?php endforeach;?>