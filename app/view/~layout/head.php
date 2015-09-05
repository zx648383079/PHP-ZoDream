<?php
	
use App\Main;	
use App\Lib\Auth;
?>

<!DOCTYPE html>
<html lang="<?php Main::ech('lang','zh-CN');?>">
<head>

<meta name="viewport" content="width=device-width, initial-scale=1"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Find Me，找到我" />

<title><?php Main::ech('title');?></title>

<?php Main::jcs(Main::$extra,'fabric.css','fabric.components.css','zx.css');?>

</head>
<body>

<div class="ms-NavBar">
  <div class="ms-NavBar-openMenu js-openMenu">
    <i class="ms-Icon ms-Icon--menu"></i>
  </div>
  <ul class="ms-NavBar-items">
    <li class="ms-NavBar-item"><a class="ms-NavBar-link" href="<?php Main::url(); ?>">首页</a></li>
    <?php if(Main::role('2')){?>
    <li class="ms-NavBar-item"><a class="ms-NavBar-link" href="<?php Main::url('?s=a'); ?>">后台</a></li>
   <?php } ?>
    <li class="ms-NavBar-item ms-NavBar-item--search ms-u-hiddenSm">
      <div class="ms-TextField">
        <input class="ms-TextField-field" placeholder="搜索">
      </div>
    </li>
    <li class="ms-NavBar-item ms-NavBar-item--right">
      <a class="ms-NavBar-link" href="<?php if(Auth::guest()){Main::url('?c=auth');}else{Main::url('?c=auth&v=logout');}; ?>">
      <i class="ms-Icon ms-Icon--person"></i><?php if(Auth::guest()){echo '登录';}else{
        echo Auth::user()->name.'(登出)';}
        ?>
      </a>
     </li>
  </ul>
</div>
