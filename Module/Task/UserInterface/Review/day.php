<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream';
?>

<div class="time-box">
    <div class="time-header">
        <?php for($i = 0; $i < 24; $i ++):?>
        <section><?=$i < 10 ? '0'.$i : $i?>:00</section>
        <?php endfor;?>
    </div>
    <div class="time-body">
        
    </div>
</div>