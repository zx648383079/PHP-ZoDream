<?php
use Zodream\Template\View;
/** @var $this View */
$js = <<<JS
$("#time").datetimer();
$("#time2").datetimer({
    title: 'y-m',
    format: 'y-m-d'
});
var d3 = $("#time3").datetimer({
    title: 'y-m',
    format: 'y-m-d'
});
var d4 = $("#time4").datetimer({
    title: 'y-m',
    format: 'y-m-d',
    min: d3
});
JS;

$this->extend('layout/header')
    ->registerCssFile('@datetimer.min.css')
    ->registerJsFile('@jquery.datetimer.min.js')
    ->registerJs($js, View::JQUERY_READY);
?>

    <input type="text" id="time">

    <input type="text" id="time2">
    <br/>
    <input type="text" id="time3">

    <input type="text" id="time4">

<?php
$this->extend('layout/footer');
?>