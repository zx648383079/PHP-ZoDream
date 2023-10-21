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
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    </div>
<?= Form::close('id') ?>