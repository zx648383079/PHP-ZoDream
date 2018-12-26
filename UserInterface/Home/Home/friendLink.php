<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '友情链接';
$this->extend('layouts/header');
?>

<div class="container">
    <?=$this->node('friend-link')?>
</div>

<?php $this->extend('layouts/footer')?>