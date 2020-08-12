<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '购买服务';
$this->extend('layouts/main');
?>

<div class="container main-box">
    
    <?=Form::open('./order/save', null, ['class' => ''])?>
        <div class="line-item">
            <span>服务</span>
            <span class="name">[<?=$service->category->name?>]<?=$service->name?></span>
        </div>
        <div class="line-tip">
            <?=$service->brief?>
        </div>
        <?php foreach($service->form as $item):?>
        <div class="line-item">
            <label for="form_<?=$item['name']?>"><?=$item['label']?></label>
            <input type="text" id="form_<?=$item['name']?>" name="remark[<?=$item['name']?>]" <?=isset($item['required']) ? 'required' : ''?>>
        </div>
        <?php endforeach;?>
        <div class="line-item">
            <span>数量</span>
            <input type="number" name="amount" value="1" min="1">
        </div>
        <div class="line-item">
            <span>服务费</span>
            <span class="name"><?=$service->price?></span>
        </div>
        <button class="btn btn-primary">下单</button>
        <input type="hidden" name="service_id" value="<?=$service->id?>">
    <?= Form::close() ?>
</div>

