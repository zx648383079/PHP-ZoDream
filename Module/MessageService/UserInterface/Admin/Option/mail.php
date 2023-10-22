<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */

$this->title = '邮箱配置';
?>

<h1><?=$this->title?></h1>
<?=Form::open('./@admin/option/mail_save')?>
    <?php foreach($items as $item):?>
        <?= $item ?>
    <?php endforeach;?>    

    <div class="btn-group">
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-primary" href="<?=$this->url('./@admin/option/debug')?>">测试</a>
    </div>
<?= Form::close('id') ?>