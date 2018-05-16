<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = 'ZoDream Account Admin';
$this->extend('../layouts/header');
?>

<?php
$this->extend('../layouts/footer');
?>