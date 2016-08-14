<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\View\View */
$this->extend(array(
	'layout' => array(
		'head',
        'navbar'
	))
);
?>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-push-3">
            <h1>欢迎关注微信公众号：zxzszh</h1>
        </div>
    </div>
</div>

<?php $this->extend('layout/foot')?>