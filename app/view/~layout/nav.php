<?php
	
use App\App;	
use App\Lib\Auth;
?>

<nav class="nav">
  <div class="brand">
    ZoDream
   </div>
  <ul>
    <li class="active"><a href="<?php App::url('/'); ?>">主页</a></li>
    <li><a href="<?php App::url('?v=blog'); ?>">弹幕</a></li>
    <li><a href="new.html">动态</a></li>
   <?php if(App::role('2')){?>
    <li><a href="<?php App::url('?c=admin'); ?>">后台</a></li>
   <?php } ?>
    <li class="right"><a href="
    <?php 
    if(Auth::guest())
    {
        App::url('?c=auth">登录');
    }else{
        App::url('?c=auth&v=logout">'.Auth::user()->name.'(登出)');
    }; 
    ?></a>
    </li>
  </ul>
</nav>
