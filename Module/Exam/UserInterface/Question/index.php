<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = $question['title'];

$this->extend('layouts/main');
?>
<div class="container">
    <ul class="path">
        <li>
            <a href="<?=$this->url('/')?>" class="fa fa-home"></a>
        </li><li>
            <a href="<?=$this->url('./')?>" >题库首页</a>
        </li><li class="active">
            <a href="<?=$this->url('./course', ['id' => $course->id])?>" ><?=$course->name?></a>
        </li>
        <li class="active">
            顺序练习
        </li>
    </ul>
</div>
<div class="container">
    <?php $this->extend('./item');?>
    <div class="tool-bar">
        <div class="btn-bar">
            <button class="left gl">上一题</button>
            <button class="left gl"
                ref="next">下一题</button>
            <button class="right pt">显示答题卡</button>
            <button class="right pt">收起详解</button></div>
        <div class="msg-bar">
            <label class="">
                <input type="checkbox" checked=""
                    class="checkbox-next"><span>&nbsp;答对自动下一题</span>
            </label>
            <span class="left">
                <span
                    class="gray">答对：</span><span class="count-right">1&nbsp;题</span>
            </span>
            <span
                class="left">
                <span class="gray">答错：</span>
                <span class="count-wrong">2&nbsp;题</span>
                </span>
            <span class="left">
                <span
                    class="gray">正确率：</span>33%</span>
        </div>
    </div>
</div>