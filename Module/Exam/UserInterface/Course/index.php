<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '';
$this->extend('layouts/main');
?>
<div class="container">
    <ul class="path">
        <li>
            <a href="<?=$this->url('/')?>" class="fa fa-home"></a>
        </li><li>
            <a href="<?=$this->url('./')?>" >题库首页</a>
        </li><li class="active">
            <?=$course->name?>
        </li>
    </ul>
</div>
<div class="container">
    <div class="course-header">
        <h1><?=$course->name?></h1>
        <h2><?=$course->description?></h2>
    </div>
    <?php foreach($course_list as $group):?>
    <div class="exam-group">
        <div class="group-header">
            <a href="<?=$this->url('./course', ['id' => $group->id])?>"><?=$group['name']?></a>
        </div>
        <div class="group-body">
            <?php foreach($group['children'] as $item):?>
            <div class="course-item">
                <div class="thumb">
                    <img src="<?=$this->asset('images/zd.jpg')?>" alt="">
                </div>
                <div class="info">
                    <div class="name">
                        <a href="<?=$this->url('./course', ['id' => $item->id])?>"><?=$item['name']?></a>
                    </div>
                    <p><?=$this->text($item['description'], 20)?></p>
                </div>
            </div>
            <?php endforeach;?>
        </div>
    </div>
    <?php endforeach;?>

    <div class="menu-box">
        <a href="<?=$this->url('./pager', ['course' => $course->id])?>">
            <i class="fa fa-sort-amount-down"></i>
            顺序练习</a>
        <a href="<?=$this->url('./pager', ['course' => $course->id, 'type' => 1])?>">
            <i class="fa fa-random"></i>
            随机练习</a>
        <a href="<?=$this->url('./pager', ['course' => $course->id, 'type' => 2])?>">
            <i class="fa fa-question-circle"></i>
            难题练习</a>
        <a href="<?=$this->url('./pager', ['course' => $course->id, 'type' => 3])?>">
            <i class="fa fa-file-alt"></i>
            全真模拟</a>
    </div>
</div>