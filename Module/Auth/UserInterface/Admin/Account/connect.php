<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '账号关联';

$passkeyUri = $this->url('./', false);
$js = <<<JS
bindAuth('{$passkeyUri}');
JS;
$this->registerJsFile('@base64.min.js')->registerJs($js);
?>
<div class="panel-container">
    <a class="register-webauth" href="javascript:;">注册生物识别</a>

    <?php foreach($model_list as $item):?>
    <div class="connect-item">
        <div class="item-name">
            <i class="fab <?=$item['icon']?>"></i>
            <?=$item['name']?>
            <?php if (isset($item['nickname'])):?>
            (<?=$this->text($item['nickname'])?>)
            <?php endif;?>
        </div>
        <div class="item-action">
            <?php if (isset($item['id'])):?>
                <a data-type="del" href="<?=$this->url('./@admin/account/delete_connect', ['id' => $item['id']])?>">解绑</a>
            <?php endif;?>
        </div>
    </div>
    <?php endforeach; ?>

    <?php if(empty($model_list)):?>
    <div class="page-empty-tip">
        空空如也~~
    </div>
    <?php endif;?>
</div>

