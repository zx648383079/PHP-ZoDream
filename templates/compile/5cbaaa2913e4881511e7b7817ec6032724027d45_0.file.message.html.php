<?php /* Smarty version 3.1.27, created on 2015-08-18 05:10:37
         compiled from "templates\message.html" */ ?>
<?php
/*%%SmartyHeaderCode:2845055d2a22dcf9625_24432942%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5cbaaa2913e4881511e7b7817ec6032724027d45' => 
    array (
      0 => 'templates\\message.html',
      1 => 1439798064,
      2 => 'file',
    ),
    'b0dd5477652a4dbb12c097f08e859dce4b66583b' => 
    array (
      0 => 'templates\\layout.html',
      1 => 1439802134,
      2 => 'file',
    ),
    '9e85753931bde8fddfd673262c0ee8b11582a8ab' => 
    array (
      0 => '9e85753931bde8fddfd673262c0ee8b11582a8ab',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '2845055d2a22dcf9625_24432942',
  'variables' => 
  array (
    'lang' => 0,
    'title' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55d2a22dd5ef47_68579089',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55d2a22dd5ef47_68579089')) {
function content_55d2a22dd5ef47_68579089 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '2845055d2a22dcf9625_24432942';
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
$_smarty_tpl->properties['nocache_hash'] = '2845055d2a22dcf9625_24432942';
?>

	
	<div class="container">
		
		<div>
			<table>
				<tr>
					<th>ID</th>
					<th>用户</th>
					<th>类型</th>
					<th>内容</th>
					<th>时间</th>
					<th>操作</th>
				</tr>
		</div>

		
		<div>
			<form action="" method="POST">
				<div class="row">
					<input type="number" name="id"/>
				</div>
				<div class="row">
					<input type="text" name="type"/>
				</div>
				<div class="row">
					<textarea rows="4" name="content"></textarea>
				</div>
				<div class="row">
					<button type="submit">提交</button>
				</div>
			</form>
		</div>
	</div>
	
	
	


<?php echo jcs(array('name'=>'angular.min'),$_smarty_tpl);?>

<?php echo jcs(array('name'=>'zx'),$_smarty_tpl);?>

<?php echo jcs(array('name'=>'controller'),$_smarty_tpl);?>


</body>
</html><?php }
}
?>