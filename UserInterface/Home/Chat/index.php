<?php
defined('APP_DIR') or exit();
use Zodream\Domain\Access\Auth;
/** @var $this \Zodream\Template\View */

$this->title = $title;
$send = Auth::user();
$css = <<<CSS
.chat li.left:before {
  background-image: url("{$user['avatar']}");
}
.chat li.right:before {
  background-image: url("{$send['avatar']}");
}
CSS;
$time = time();
$js = <<<JS
var USER = "{$user['id']}",
    time = "{$time}";
require(["home/chat"]);
JS;

$this->registerCssFile('zodream/chat.css');
$this->registerCss($css);
$this->registerJs($js);

$this->extend([
    'layout/header',
    'layout/navbar'
]);
?>
<div class="container">
    <div class="row">
        <ul class="chat">
            <?php for ($i = count($data) - 1; $i >= 0; $i --):?>
                <li class="<?=$data[$i]['send_id'] == $send['id'] ? 'right' : 'left'?>">
                    <p><?=$data[$i]['content']?></p>
                    <p><?=$this->time($data[$i]['create_at'])?></p>
                </li>
            <?php endfor;?>
        </ul>
    </div>
    <div class="row">
        <textarea class="form-control content" rows="3"></textarea>
        <div class="col-md-push-9 col-md-2">
            <button class="btn btn-primary send" type="button">发送</button>
        </div>
        
    </div>
</div>

<?php $this->extend('layout/footer')?>