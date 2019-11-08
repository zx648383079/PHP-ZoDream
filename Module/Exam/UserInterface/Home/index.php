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
        </li><li class="active">
            题库首页
        </li>
    </ul>
</div>
<div class="container">
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
                </div>
            </div>
            <?php endforeach;?>
        </div>
    </div>
    <?php endforeach;?>
</div>