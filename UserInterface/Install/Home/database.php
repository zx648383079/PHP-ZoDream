<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '设置您的数据库连接';
$js = <<<JS
bindDB();
JS;
$this->registerJs($js);
?>
<div class="page-header">
	设置您的数据库连接
</div>
<div class="page-body">
	<form method="post" action="<?=$this->url('./import')?>">
		<p>请在下方填写您的数据库连接信息。如果您不确定，请联系您的服务提供商。</p>
		<table class="table table-hover">
			<tr>
				<th>用户名</th>
				<td><input name="db[user]" type="text" class="form-control" size="25" value="root" datatype="*" required placeholder="用户名" /></td>
				<td>您的MySQL用户名</td>
			</tr>
			<tr>
				<th>密码</th>
				<td><input name="db[password]" type="password" class="form-control" size="25" placeholder="密码" datatype="*" autocomplete="off" /></td>
				<td>&hellip;及其密码</td>
			</tr>
			<tr>
				<th>数据库主机</th>
				<td><input name="db[host]" type="text" class="form-control" size="25" value="localhost" datatype="*"/></td>
				<td>如果<code>localhost</code> 和 <code>127.0.0.1</code>不能用，您通常可以从网站服务提供商处得到正确的信息。</td>
			</tr>
			<tr>
				<th>数据库端口</th>
				<td><input name="db[port]" type="text" class="form-control" size="25" value="3306" datatype="*"/></td>
				<td>默认是3306</td>
			</tr>
			<tr>
				<th>表前缀</th>
				<td><input name="db[prefix]" type="text" class="form-control" value="" size="25"/></td>
				<td>如果您希望在同一个数据库安装多个zodream，请修改前缀。</td>
			</tr>
			<tr>
				<th>数据库名</th>
				<td><input name="db[database]" type="text" class="form-control" size="25" required value="zodream" datatype="*"/></td>
				<td>安装到哪个数据库？</td>
			</tr>
		</table>
	</form>
</div>
<div class="page-footer">
	<a class="btn btn-primary" href="<?=$this->url('./module');?>">下一步</a>
</div>