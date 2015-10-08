<?php 
use App\Lib\Object\OTime;
App::extend(array('~layout' => array('head','nav')));
?>

<div class="container">
  <div class="panel">
    <h2 class="head">
      <?php 
      $data = App::ret('data');
      echo '<span class="no">',$data->id,'</span>',$data->title;
      ?>
    </h2>
    <div class="body">
      <?php echo $data->content; ?>
    </div>
    <div class="foot">
      发表于：<?php echo OTime::to($data->cdate); ?>
    </div>
  </div>
</div>

<?php App::extend('~layout.foot');?>