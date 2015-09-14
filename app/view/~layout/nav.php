<?php
	
use App\App;	
use App\Lib\Auth;
?>

<div class="ms-NavBar">
  <div class="ms-NavBar-openMenu js-openMenu">
    <i class="ms-Icon ms-Icon--menu"></i>
  </div>
  <ul class="ms-NavBar-items">
    <li class="ms-NavBar-item"><a class="ms-NavBar-link" href="<?php App::url(); ?>">首页</a></li>
    <?php if(App::role('2')){?>
    <li class="ms-NavBar-item"><a class="ms-NavBar-link" href="<?php App::url('?c=admin'); ?>">后台</a></li>
   <?php } ?>
    <li class="ms-NavBar-item ms-NavBar-item--search ms-u-hiddenSm">
      <div class="ms-TextField">
        <input class="ms-TextField-field" placeholder="搜索">
      </div>
    </li>
    <li class="ms-NavBar-item ms-NavBar-item--right">
      <a class="ms-NavBar-link" href="<?php if(Auth::guest()){App::url('?c=auth');}else{App::url('?c=auth&v=logout');}; ?>">
      <i class="ms-Icon ms-Icon--person"></i><?php if(Auth::guest()){echo '登录';}else{
          echo Auth::user()->name.'(登出)';
        }
        ?>
      </a>
     </li>
  </ul>
</div>