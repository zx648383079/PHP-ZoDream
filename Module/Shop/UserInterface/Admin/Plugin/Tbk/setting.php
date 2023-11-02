<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
use Zodream\Html\Dark\Theme;
/** @var $this View */
$this->title = '淘宝客设置';
?>
<h1><?=$this->title?></h1>
<?=Form::open('./@admin/plugin/tbk/setting')?>
    <?=Theme::text('option[taobaoke][app_key]', $data['app_key'], 'APP KEY', '', true)?>
    <?=Theme::text('option[taobaoke][secret]', $data['secret'], 'Secret', '', true)?>
    
    <div class="btn-group">
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    </div>
<?= Form::close() ?>