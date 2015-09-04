<?php
	
use App\Main;	
?>

<!DOCTYPE html>
<html lang="<?php echo isset($lang)?$lang:'zh-CN';?>">
<head>

<meta name="viewport" content="width=device-width, initial-scale=1"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Find Me，找到我" />

<title><?php echo $title;?></title>

<?php Main::jcs(Main::$extra,'fabric.css','fabric.components.css','zx.css');?>

</head>
<body>

<div class="ms-NavBar">
  <div class="ms-NavBar-openMenu js-openMenu">
    <i class="ms-Icon ms-Icon--menu"></i>
  </div>
  <ul class="ms-NavBar-items">
    <li class="ms-NavBar-item"><a class="ms-NavBar-link" href="<?php Main::url(); ?>">首页</a></li>
    <?php if(Main::role(2)){?>
    <li class="ms-NavBar-item"><a class="ms-NavBar-link" href="<?php Main::url('?s=a'); ?>">后台</a></li>
   <?php } ?>
    <li class="ms-NavBar-item ms-NavBar-item--search ms-u-hiddenSm">
      <div class="ms-TextField">
        <input class="ms-TextField-field" placeholder="搜索">
      </div>
    </li>
    <li class="ms-NavBar-item ms-NavBar-item--right"><a class="ms-NavBar-link" href="<?php Main::url('?c=auth'); ?>"><i class="ms-Icon ms-Icon--person"></i>登录</a></li>
  </ul>
</div>
