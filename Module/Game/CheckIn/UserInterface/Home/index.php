<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
?>

<?php if($model):?>
<div class="check-box checked">
    <div class="check-btn">
        已签到
        <em>已连续签到<?=$model->running?>天</em>
    </div>
</div>
<?php else:?>
<div class="check-box">
    <a href="<?=$this->url('./home/check_in')?>" class="check-btn" data-toggle="签到|已签到">
        签到
    </a>
</div>
<?php endif;?>

