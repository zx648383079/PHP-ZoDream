<?php /* Smarty version 3.1.27, created on 2015-08-17 12:22:55
         compiled from "templates\index.html" */ ?>
<?php
/*%%SmartyHeaderCode:1544455d1b5ffc76b22_22831426%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4168cf2c7dda6b349f352305cbbe4c65600adad2' => 
    array (
      0 => 'templates\\index.html',
      1 => 1439804861,
      2 => 'file',
    ),
    'b0dd5477652a4dbb12c097f08e859dce4b66583b' => 
    array (
      0 => 'templates\\layout.html',
      1 => 1439802134,
      2 => 'file',
    ),
    'c4877170b5f164f5d9d15cf864da396508c295b7' => 
    array (
      0 => 'c4877170b5f164f5d9d15cf864da396508c295b7',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '1544455d1b5ffc76b22_22831426',
  'variables' => 
  array (
    'lang' => 0,
    'title' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55d1b5ffcdc438_42091433',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55d1b5ffcdc438_42091433')) {
function content_55d1b5ffcdc438_42091433 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '1544455d1b5ffc76b22_22831426';
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
$_smarty_tpl->properties['nocache_hash'] = '1544455d1b5ffc76b22_22831426';
?>

	
	<div class="container" ng-app ng-init="aa='aaa'" ng-controller="phonelist">
		<div><a href="/auth/logout">登出</a></div>
		
		<canvas id="cas" height="500" width="700">
			<p>不支持HTM5</p>
		</canvas>
		
		<div class="head">
			Hello {{'World'}}!
			<br/>
			输入数据：<input type="text" ng-model="name" placeholder="World">
			<br/>
			
			{{ name || 'word' }}
			
			<p>1+2= {{ 1+2 }}</p>
			<select ng-model="orderProp">
			  <option value="name">Alphabetical</option>
			  <option value="age">Newest</option>
			</select>
			
			<ul>
				<li ng-repeat="phone in phones | filter:name | orderBy:orderProp">
					{{ phone.name }}
					<p>{{ phone.text }}</p>
				</li>
			</ul>
			
			{{ phones.length }}
			{{ hello }}
			
			<br/>
			
			<table>
				<tr><th>fdgd</th></tr>
				<tr ng-repeat="i in [0,1,2,3,4,5,6,7,8]">
					<td>{{ i+1 }}</td>
				</tr>
			</table>
		</div>
	</div>
	
	
	<form>
			
	</form>
	
	


<?php echo jcs(array('name'=>'angular.min'),$_smarty_tpl);?>

<?php echo jcs(array('name'=>'zx'),$_smarty_tpl);?>

<?php echo jcs(array('name'=>'controller'),$_smarty_tpl);?>


</body>
</html><?php }
}
?>