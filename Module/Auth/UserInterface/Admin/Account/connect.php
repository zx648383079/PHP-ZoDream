<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '账号关联';

$js = <<<JS
bindAuth(1);
JS;
$this->registerJs($js);
?>
<a class="register-webauth" href="javascript:;">注册生物识别</a>

<?php foreach($model_list as $item):?>
<div class="connect-item">
    <div class="nmae">
        <i class="fab <?=$item['icon']?>"></i>
        <?=$item['name']?>
        <?php if (isset($item['nickname'])):?>
        (<?=$this->text($item['nickname'])?>)
        <?php endif;?>
    </div>
    <div class="action">
        <?php if (isset($item['id'])):?>
            <a data-type="del" href="<?=$this->url('./@admin/account/delete_connect', ['id' => $item['id']])?>">解绑</a>
        <?php endif;?>
    </div>
</div>
<?php endforeach; ?>

