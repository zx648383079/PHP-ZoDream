<?php
defined('APP_DIR') or exit();
$this->extend(array(
    'layout' => array(
        'head'
    ))
);
?>

<div class="main">
<h1>完成</h1>
<p>成功完成相关设置，请尽情享受。。。</p>
<a class="btn" href="<?php $this->url('index.php');?>">进入首页</a>
</div>

<?php
$this->extend(array(
	'layout' => array(
		'foot'
	))
);
?>