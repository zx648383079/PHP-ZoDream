<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '族谱列表';
$this->registerCssFile('@family.min.css');
?>

<?php foreach($clan_list as $item):?>
   <a class="clan-item" href="<?=$this->url('./', ['clan' => $item->id])?>">
       <div class="cover">
           <img src="<?=$item->cover?>" alt="">
       </div>
       <div class="name"><?=$item->name?></div>
    </a>
<?php endforeach;?>

<div align="center">
    <?=$clan_list->getLink()?>
</div>
