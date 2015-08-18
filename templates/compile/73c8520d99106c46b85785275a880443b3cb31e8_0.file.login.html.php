<?php /* Smarty version 3.1.27, created on 2015-08-18 05:24:29
         compiled from "templates\login.html" */ ?>
<?php
/*%%SmartyHeaderCode:1851555d2a56d379f20_04236150%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '73c8520d99106c46b85785275a880443b3cb31e8' => 
    array (
      0 => 'templates\\login.html',
      1 => 1439804766,
      2 => 'file',
    ),
    'b0dd5477652a4dbb12c097f08e859dce4b66583b' => 
    array (
      0 => 'templates\\layout.html',
      1 => 1439802134,
      2 => 'file',
    ),
    'abb392e070d6f519f74c90ee8975c27b5d99e570' => 
    array (
      0 => 'abb392e070d6f519f74c90ee8975c27b5d99e570',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '1851555d2a56d379f20_04236150',
  'variables' => 
  array (
    'lang' => 0,
    'title' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55d2a56d4be2f8_15941800',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55d2a56d4be2f8_15941800')) {
function content_55d2a56d4be2f8_15941800 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '1851555d2a56d379f20_04236150';
?>
<!DOCTYPE html>
<html lang="<?php echo $_smarty_tpl->tpl_vars['lang']->value;?>
">
<head>

<meta name="viewport" content="width=device-width, initial-scale=1"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Find Me，找到我" />

<title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>

<?php echo jcs(array('name'=>'zx.css'),$_smarty_tpl);?>


</head>
<body>

	<div class="navbar">
		<div class="brand">
			<img src="/asset/img/favicon.png" alt=""/>
		</div>
		<ul class="inline">
			<li><a href="/">主页</a></li>
			<li><a href="/message">消息</a>
				<ul>
					<li><a href="#">普通消息</a></li>
					<li><a href="#">图文消息</a></li>
					<li><a href="">消息</a>
						<ul>
							<li><a href="#">普通消息</a></li>
							<li><a href="#">图文消息</a></li>
						</ul>
					</li>
					<li><a href="#">图文消息</a></li>
				</ul>
			</li>
			<li><a href="#">会员</a></li>
			<li><a href="#">回复</a></li>
		</ul>
	</div>
	
	
<?php
$_smarty_tpl->properties['nocache_hash'] = '1851555d2a56d379f20_04236150';
?>

	
	<div class="form" ng-app="formApp" ng-controller="formController">
		<form name="myForm" ng-submit="sendForm()">
			<div class="row">
				<input type="email" name="email" placeholder="账号" required ng-model="formData.email"/>
				<span ng-show="myForm.email.$error.required">*这是必须的</span>
				<span ng-show="myForm.email.$error.email">*请输入正确的邮箱</span>
				<span class="help-block" ng-show="errorEmail">{{ errorEmail }}</span>
			</div>
			<div class="row">
				<input type="password" name="pwd" placeholder="密码" required ng-minlength="6" ng-model="formData.pwd"/>
				<span ng-show="myForm.pwd.$error.required">*这是必须的</span>
				<span class="error" ng-show="myForm.pwd.$error.minlength">*请输入6位以上的密码</span>
				<span class="help-block" ng-show="errorPwd">{{ errorPwd }}</span>
			</div>
			<div class="row">
				<input type="text" name="code" placeholder="验证码" required ng-model="formData.code"/>
				<img id="code" src="/verify" alt="验证码"/>
				<span ng-show="myForm.code.$error.required">*这是必须的</span>
				<span class="help-block" ng-show="errorCode">{{ errorCode }}</span>
			</div>
			<div class="row" ng-show="message">{{ message }}</div>
			<div class="row">
				<button type="submit">提交</button>
				<a href="/auth/qrcode">二维码</a>
			</div>
		</form>
	
	</div>
	
	


<?php echo jcs(array('name'=>'angular.min'),$_smarty_tpl);?>

<?php echo jcs(array('name'=>'zx'),$_smarty_tpl);?>

<?php echo jcs(array('name'=>'controller'),$_smarty_tpl);?>


</body>
</html><?php }
}
?>