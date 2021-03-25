<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '选择安装模块';
$js = <<<JS
bindModule();
JS;
$this->registerJs($js);
?>
<div class="page-header">
	选择安装模块
</div>
<div class="page-body">
	<form method="post" action="<?=$this->url('./import_module')?>">
		<p>请在选择安装模块。</p>
		<div class="module-box">
			<?php foreach($module_list as $item):?>
			<p>
				<input type="checkbox" name="module[checked][]" value="<?=$item?>"><?=$item?>&nbsp;&nbsp;&nbsp;&nbsp;路径：
				<input type="text" name="module[uri][<?=$item?>]" class="form-control" size="10" placeholder="请输入路径">
			</p>
			<?php endforeach;?>
		</div>
		<p>请填写管理员账号。</p>
		<table class="table table-hover">
			<tr>
				<th>管理员邮箱</th>
				<td><input name="user[email]" type="email" class="form-control" size="25" value="" datatype="*" required placeholder="邮箱" /></td>
				<td>您的管理员登录账户</td>
			</tr>
			<tr>
				<th>管理员密码</th>
				<td><input name="user[password]" type="password" class="form-control" size="25" placeholder="密码" datatype="*" autocomplete="off" /></td>
				<td>您的管理员登录密码</td>
			</tr>
		</table>
	</form>
</div>
<div class="page-footer">
	<a class="btn btn-primary" href="<?=$this->url('./complete');?>">下一步</a>
</div>