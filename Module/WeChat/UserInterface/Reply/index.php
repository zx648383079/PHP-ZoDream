<?php
use Zodream\Template\View;
/** @var $this View */
$this->extend('layout/header');
?>

<div>
    <?php $this->extend('layout/editor'); ?>
</div>

<?php
$this->extend('layout/footer');
?>