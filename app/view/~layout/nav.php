<?php
	
use App\Lib\Auth;
?>

<nav class="nav">
  <div class="brand">
    ZoDream
   </div>
  <ul>
    <li<?php echo App::ret('nav' , 0) == 0?' class="active"':'' ?>><a href="<?php App::url('/'); ?>">首页</a></li>
    <li<?php echo App::ret('nav') == 1?' class="active"':'' ?>><a href="<?php App::url('?v=about'); ?>">关于</a></li>
    <li<?php echo App::ret('nav') == 2?' class="active"':'' ?>><a href="<?php App::url('?v=download'); ?>">下载</a></li>
    <li<?php echo App::ret('nav') == 3?' class="active"':'' ?>><a href="<?php App::url('?v=document'); ?>">文档</a></li>
   <?php if(App::role('2')){?>
    <li<?php echo App::ret('nav') == 4?' class="active"':'' ?>><a href="<?php App::url('?c=admin'); ?>">后台</a></li>
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
