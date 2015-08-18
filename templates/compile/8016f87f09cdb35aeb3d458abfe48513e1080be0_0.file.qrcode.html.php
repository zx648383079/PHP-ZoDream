<?php /* Smarty version 3.1.27, created on 2015-08-18 05:24:31
         compiled from "templates\qrcode.html" */ ?>
<?php
/*%%SmartyHeaderCode:571755d2a56f33be47_09273022%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8016f87f09cdb35aeb3d458abfe48513e1080be0' => 
    array (
      0 => 'templates\\qrcode.html',
      1 => 1439806033,
      2 => 'file',
    ),
    'b0dd5477652a4dbb12c097f08e859dce4b66583b' => 
    array (
      0 => 'templates\\layout.html',
      1 => 1439802134,
      2 => 'file',
    ),
    '26dd65d3db7a117f3e793636aaa405923f0e3aae' => 
    array (
      0 => '26dd65d3db7a117f3e793636aaa405923f0e3aae',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '571755d2a56f33be47_09273022',
  'variables' => 
  array (
    'lang' => 0,
    'title' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55d2a56f395bd5_11010359',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55d2a56f395bd5_11010359')) {
function content_55d2a56f395bd5_11010359 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '571755d2a56f33be47_09273022';
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
$_smarty_tpl->properties['nocache_hash'] = '571755d2a56f33be47_09273022';
?>

	
	<div class="form">
		<img src="/<?php echo $_smarty_tpl->tpl_vars['img']->value;?>
" alt="二维码"/>
	</div>
	


<?php echo jcs(array('name'=>'angular.min'),$_smarty_tpl);?>

<?php echo jcs(array('name'=>'zx'),$_smarty_tpl);?>

<?php echo jcs(array('name'=>'controller'),$_smarty_tpl);?>


</body>
</html><?php }
}
?>