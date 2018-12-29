<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '账号关联';
?>

<?php foreach($model_list as $item):?>
<div class="connect-item">
    <div class="nmae">
        <i class="fab fa-<?=$item->vendor?>"></i>
        <?=$item->vendor?>(<?=$item->nickname?>)
    </div>
    <div class="action">
        <a data-type="del" href="<?=$this->url('./admin/account/delete_connect', ['id' => $item->id])?>">解绑</a>
    </div>
</div>
<?php endforeach; ?>

