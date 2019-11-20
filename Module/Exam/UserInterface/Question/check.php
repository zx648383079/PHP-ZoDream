<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

foreach($items as $question) {
    $this->extend('Pager/view', compact('question'));
}
?>
