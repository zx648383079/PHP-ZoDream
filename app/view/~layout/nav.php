<?php
use App\Lib\Auth;
?>

<nav class="nav">
  <div class="brand">
    ZoDream
   </div>
  <ul>
    <li class="active"><a href="<?php App::url('/'); ?>">主页</a></li>
    <li><a href="<?php App::url('?v=method'); ?>">方法</a></li>
    <li><a href="<?php App::url('?v=create'); ?>">提交</a></li>
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
