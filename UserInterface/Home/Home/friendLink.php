<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '友情链接';
$this->extend('layouts/header');
?>

<div class="container">
    <div class="friend-box">
        <?=$this->node('friend-link')?>
    </div>
</div>

<?php $this->extend('layouts/footer')?>