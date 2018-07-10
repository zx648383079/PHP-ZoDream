<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '编辑地址';

$this->extend('../layouts/header');
?>

<div class="has-header">
    <form action="" method="post">
        <div class="input-group">
            <input type="text" >
        </div>
        <div class="input-group">
            <input type="text">
        </div>
        <div class="input-group">
            <span>地址</span>
        </div>
        <div class="input-group">
            <textarea name=""></textarea>
        </div>

        <div class="input-radio">
            <span>设为默认地址</span>
            <label for="">
                <input type="radio" name="" id="">
            </label>
        </div>

        <button type="button">删除地址</button>
    </form>
</div>

<?php $this->extend('../layouts/navbar');?>