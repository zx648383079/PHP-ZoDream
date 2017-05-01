<?php
defined('APP_DIR') or exit();
use Zodream\Service\Routing\Url;
use Zodream\Infrastructure\ObjectExpand\HtmlExpand;
/** @var $this \Zodream\Domain\View\View */

$this->extend([
    'layout/header',
    'layout/navbar'
]);
?>

<?php $this->extend('layout/footer')?>