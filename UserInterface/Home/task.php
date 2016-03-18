<?php
defined('APP_DIR') or exit();
$this->extend(array(
	'layout' => array(
		'head'
	))
);
//$page = $this->get('page');
?>
<div class="zd-container">
<div class="ms-Grid off">
	<div class="ms-Grid-row">
        <div class="ms-Grid-col ms-u-md12">
            <h3 class="headTitle">任务列表</h3>
            <div class="ms-Table">
                <div class="ms-Table-row">
                    <span class="ms-Table-cell">任务名</span>
                    <span class="ms-Table-cell">进程</span>
                    <span class="ms-Table-cell">负责人</span>
                    <span class="ms-Table-cell">更新时间</span>
                </div>
                <div class="ms-Table-row">
                    <span class="ms-Table-cell">File name</span>
                    <span class="ms-Table-cell">Location</span>
                    <span class="ms-Table-cell">Modified</span>
                    <span class="ms-Table-cell">Type</span>
                </div>
            </div>
        </div>
	</div>
	<div class="ms-Grid-row">
        <div class="ms-Grid-col ms-u-mdPush2 ms-u-md8">
            <h3 class="headTitle">提交任务</h3>
            <form method="post" action="<?php $this->url();?>">
                <div class="ms-TextField">
                    <input type="text" name="name" class="ms-TextField-field" placeholder="名称" value="<?php $this->ech('name');?>">
                </div>
                <div class="ms-TextField ms-TextField--multiline">
                    <textarea name="content" class="ms-TextField-field" placeholder="详情"></textarea>
                </div>
                <p class="text-danger"><?php $this->ech('status'); ?></p>
                <button class="ms-Button">提交</button>
            </form>
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