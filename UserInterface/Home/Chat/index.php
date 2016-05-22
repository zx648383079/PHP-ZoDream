<?php
defined('APP_DIR') or exit();
use Zodream\Domain\Authentication\Auth;
/** @var $this \Zodream\Domain\Response\View */
$user = $this->get('user');
$send = Auth::user();
$css = <<<CSS
.chat li.left:before {
  background-image: url("{$user['avatar']}");
}
.chat li.right:before {
  background-image: url("{$send['avatar']}");
}
CSS;
$js = <<<JS
var USER = "{$user['id']}",
    time = "{time()}";
JS;

$this->extend(array(
	'layout' => array(
		'head',
        'navbar'
	)), array(
        'zodream/chat.css',
        '!css '.$css,
        '!js '.$js
    )
);
$data = $this->get('data', array());
?>
<div class="container">
    <div class="row">
        <ul class="chat">
            <li></li>
        </ul>
    </div>
    <div class="row">
        <textarea class="form-control content" rows="3"></textarea>
        <div class="col-md-push-9 col-md-2">
            <button class="btn btn-primary send" type="button">发送</button>
        </div>
        
    </div>
</div>
<?php
$this->extend(array(
	'layout' => array(
		'foot'
	)), array(
        '!js require(["home/chat"]);'
    )
);
?>