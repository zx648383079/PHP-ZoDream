<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Template\View */

$this->title = $title;
$this->registerJs('require(["home/talk"]);');
$this->registerCssFile('zodream/talk.css');
$this->extend([
    'layout/header',
    'layout/navbar'
]);
?>

<div class="content">
  <div class="wrapper">
    <hr class="line-left">
    <hr class="line-right">
    <div class="talk">
      <h1 class="title"><?=$title?></h1>
      <?php foreach ($data as $value) :?>
         <div class="year">
            <h2><a href="javascript:0;"><?=date('Y年', $value[0]['create_at']);?><i></i></a></h2>
            <div class="list">
                <ul>
                    <?php foreach ($value[1] as $item) :?>
                    <li class="cls">
                        <div class="date"><?=date('m月d日', $item['create_at']);?></div>
                        <div class="intro"><?=$item['content'];?></div>
                    </li>
                    <?php endforeach;?>
                </ul>
            </div>
       </div>
    <?php endforeach;?>
    </div>
  </div>
</div>

<?php $this->extend('layout/footer')?>