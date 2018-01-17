<?php
use Zodream\Template\View;
/** @var $this View */

$this->title = $model->id > 0 ? '编辑' : '新增'.'流水';

$js = <<<JS
    $('[name=happened_at]').datetimer();
JS;


$this->extend('layouts/header')
    ->registerJs($js, View::JQUERY_READY);
?>

    <h1><?=$this->title?></h1>
    <form data-type="ajax" action="<?=$this->url('./income/save_log')?>" method="post" class="form-table" role="form">
        <div class="input-group">
            <label>类型</label>
            <input value="1" name="type" type="radio" <?=$model->type < 1 ? 'checked': ''?>> 收入
            <input value="0" name="type" type="radio" <?=$model->type == 1 ? 'checked': ''?>> 支出
        </div>
        <div class="input-group">
            <label>可用金额</label>
            <input name="money" type="text" class="form-control" placeholder="可用金额" value="<?=$model->money?>">
        </div>
        <div class="input-group">
            <label>冻结金额</label>
            <input name="frozen_money" type="text" class="form-control" placeholder="冻结金额" value="<?=$model->frozen_money?>">
        </div>
        <div class="input-group">
            <label>资金账户</label>
            <select class="form-control" name="account_id">
                <?php foreach($account_list as $item):?>
                    <option value="<?=$item->id;?>" <?=$model->id == $model->account_id ? 'selected' : ''?>><?=$item->name?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div id="project_id-box" class="input-group">
            <label>理财项目</label>
            <select class="form-control" name="project_id">
                <?php foreach($project_list as $item):?>
                    <option value="<?=$item->id;?>" <?=$model->id == $model->project ? 'selected' : ''?>><?=$item->name?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div id="channel-box" class="input-group">
            <label>消费渠道</label>
            <select class="form-control" name="channel_id">
                <?php foreach($channel_list as $item):?>
                    <option value="<?=$item->id;?>" <?=$model->id == $model->channel_id ? 'selected' : ''?>><?=$item->name?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="input-group">
            <label>发生时间</label>
            <input name="happened_at" type="text" class="form-control" placeholder="选择发生时间" value="<?=$model->happened_at?>">
        </div>
        <div class="input-group">
            <label>说明</label>
            <textarea name="remark" class="form-control" placeholder="备注信息"><?=$model->remark?></textarea>
        </div>
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
        <input type="hidden" name="id" value="<?=$model->id?>">
    </form>

<?php
$this->extend('layouts/footer');
?>