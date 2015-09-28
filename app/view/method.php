<?php 
App::extend(array('~layout' => array('head','nav')));
?>

<div class="container">
  <div class="panel">
    <h2 class="head">
      <?php App::ech('data.title'); ?>
    </h2>
    <div class="body">
      <?php App::ech('data.content'); ?>
    </div>
    <div class="foot">
      发表于：<?php App::ech('data.cdate'); ?>
    </div>
  </div>
</div>

<?php App::extend('~layout.foot');?>