<?php 

use App\App;

?>

<div class="container">

<!-- left menu -->
<div class="short">
  <ul class="menu">
    <li class="active"><a href="<?php App::url('?c=admin');?>">动态</a></li>
    <li><a href="<?php App::url('?c=admin&v=wechat');?>">微信</a></li>
    <li><a href="<?php App::url('?c=admin&v=blog');?>">博客</a></li>
    <li><a href="<?php App::url('?c=admin&v=users');?>">用户</a></li>
    <li><a href="<?php App::url('?c=admin&v=mysql');?>">数据</a></li>
    <li><a href="<?php App::url('?c=admin&v=about');?>">关于</a></li>
  </ul>
</div>
