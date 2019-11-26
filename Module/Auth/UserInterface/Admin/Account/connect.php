<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '账号关联';

$icon_list = [
    'wx' => 'weixin'
];

$js = <<<JS
bindAuth(1);
JS;
$this->registerJs($js);
?>
<a class="register-webauth" href="javascript:;">注册生物识别</a>

<?php foreach($model_list as $item):?>
<div class="connect-item">
    <div class="nmae">
        <i class="fab fa-<?=isset($icon_list[$item->vendor]) ? $icon_list[$item->vendor] : $item->vendor?>"></i>
        <?=$item->vendor?>(<?=$item->nickname?>)
    </div>
    <div class="action">
        <a data-type="del" href="<?=$this->url('./@admin/account/delete_connect', ['id' => $item->id])?>">解绑</a>
    </div>
</div>
<?php endforeach; ?>

