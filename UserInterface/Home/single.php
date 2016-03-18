<?php
use Zodream\Infrastructure\ObjectExpand\TimeExpand;
defined('APP_DIR') or exit();
$this->extend(array(
    'layout' => array(
        'head'
    ))
);
?>

<div class="zd-container">
<div class="ms-Grid off">
	<div class="ms-Grid-row">
		<div class="ms-Grid-col ms-u-md12">
            <h1 class="text-center">hhhhhhhhh</h1>
            <div>hhh</div>
            <div>hhh</div>
		</div>
	</div>
    <div class="ms-Grid-row">
		<div class="ms-Grid-col ms-u-md6">
            <a class="ms-Link" href="#">
                <i class="ms-Icon ms-Icon--arrowLeft" aria-hidden="true"></i>
                上一篇：哈哈哈哈哈
            </a>
		</div>
        <div class="ms-Grid-col ms-u-md6 text-right">
            <a class="ms-Link" href="#">
                下一篇：好很好
                <i class="ms-Icon ms-Icon--arrowRight" aria-hidden="true"></i>
            </a>
		</div>
	</div>
</div>

<div class="ms-Grid">
	<div class="ms-Grid-row">
        <h4 class="headTitle">评论</h4>
        
        <div class="ms-Grid-col ms-u-mdPush2 ms-u-md8">
            <form method="post" action="<?php $this->url();?>">
                <div class="ms-TextField">
                    <input type="text" name="name" class="ms-TextField-field" placeholder="姓名" value="<?php $this->ech('name');?>">
                </div>
                <div class="ms-TextField">
                    <input type="email" name="email" class="ms-TextField-field" placeholder="邮箱" value="<?php $this->ech('email');?>" required>
                </div>
                <div class="ms-TextField ms-TextField--multiline">
                    <textarea name="content" class="ms-TextField-field" placeholder="内容" required></textarea>
                </div>
                <button class="ms-Button">评论</button>
                </form>
            </div>
		</div>
	</div>
</div>
</div>
<?php
$this->extend(array(
    'layout' => array(
        'foot'
    ))
);
?>