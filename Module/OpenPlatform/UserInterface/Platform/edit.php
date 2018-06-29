<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '应用';
?>
    <h1><?=$this->title?></h1>
    <form data-type="ajax" action="<?=$this->url('./platform/save')?>" method="post" class="form-table" role="form">
        
        <div class="input-group">
            <label>名称</label>
            <input name="name" type="text" class="form-control"  placeholder="输入名称" value="<?=$model->name?>">
        </div>
        <div class="input-group">
            <label>类型</label>
            <select name="type" required>
                <?php foreach($model->type_list as $key => $item):?>
                    <option value="<?=$key?>" <?=$key == $model->type ? 'selected' : ''?>><?=$item?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="input-group">
            <label>域名</label>
            <input name="domain" type="text" class="form-control"  placeholder="输入名称" value="<?=$model->domain?>">
        </div>
        <?php if ($model->id):?>
            <div class="input-group">
                <label>APP ID</label>
                <input name="appid" type="text" readonly class="form-control" value="<?=$model->appid?>">
            </div>
            <div class="input-group">
                <label>Secret</label>
                <input name="secret" type="text" readonly class="form-control" value="<?=$model->secret?>">
            </div>
            <div class="input-group">
                <label>签名方式</label>
                <select name="type" required>
                    <?php foreach($model->sign_type_list as $key => $item):?>
                        <option value="<?=$key?>" <?=$key == $model->sign_type ? 'selected' : ''?>><?=$item?></option>
                    <?php endforeach;?>
                </select>
            </div>

        <?php endif;?>

        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
        <input type="hidden" name="id" value="<?=$model->id?>">
    </form>