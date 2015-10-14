<?php 

?>

<div class="container">

<!-- left menu -->
<div class="short">
  <ul class="menu">
    <li<?php echo App::ret('menu' , 0) == 0?' class="active"':'' ?>><a href="<?php App::url('?c=admin');?>">动态</a></li>
    <li<?php echo App::ret('menu') == 1?' class="active"':'' ?>><a href="<?php App::url('?c=admin&v=system');?>">系统</a></li>
    <li<?php echo App::ret('menu') == 2?' class="active"':'' ?>><a href="<?php App::url('?c=admin&v=document');?>">文档</a></li>
    <li<?php echo App::ret('menu') == 3?' class="active"':'' ?>><a href="<?php App::url('?c=admin&v=wechat');?>">微信</a></li>
    <li<?php echo App::ret('menu') == 4?' class="active"':'' ?>><a href="<?php App::url('?c=admin&v=users');?>">用户</a></li>
    <li<?php echo App::ret('menu') == 5?' class="active"':'' ?>><a href="<?php App::url('?c=admin&v=mysql');?>">数据</a></li>
  </ul>
</div>
