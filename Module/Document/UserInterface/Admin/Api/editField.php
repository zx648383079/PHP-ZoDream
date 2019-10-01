<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\Document\Domain\Model\FieldModel;
/** @var $this View */
?>

<form action="<?=$this->url('./admin/api/save_field', false)?>" method="post" class="form-table" role="form">

    <?php if($model->kind == 3):?>
    <div class="input-group">
        <label>字段键</label>
        <input name="name" type="text" class="form-control" placeholder="字段键" value="<?=$model->name?>">
    </div>
    <div class="input-group">
        <label>字段值</label>
        <input name="default_value" type="text" class="form-control" placeholder="字段值" value="<?=$model->default_value?>">
    </div>
    <div class="input-group">
        <label>备注说明</label>
        <textarea name="remark" class="form-control" placeholder="备注说明"><?=$model->remark?></textarea>
    </div>
    <?php elseif($model->kind == 1):?>
    <div class="input-group">
        <label>参数别名</label>
        <input name="name" type="text" class="form-control" placeholder="参数别名" value="<?=$model->name?>">
    </div>
    <div class="input-group">
        <label>参数含义</label>
        <input name="title" type="text" class="form-control" placeholder="参数含义" value="<?=$model->title?>">
    </div>
    <div class="input-group">
        <label>参数类型</label>
        <select class="form-control" name="type">
            <?php foreach(FieldModel::type_list as $key => $item):?>
               <option value="<?=$key?>" <?= $key == $model->type ? 'selected' : '' ?>><?=$item?></option>
            <?php endforeach;?>
        </select>
    </div>

    <div class="input-group">
        <label>是否必传</label>
        <div class="form-group">
            
            <label class="radio-inline">
                <input type="radio" name="is_required" value="1" <?= 1 == $model->is_required ? 'checked' : '' ?> > 是
            </label>
            <label class="radio-inline">
                <input type="radio" name="is_required" value="0" <?= 0 == $model->is_required ? 'checked' : '' ?>> 否
            </label>

        </div>
    </div>

    <div class="input-group">
        <label>默认值</label>
        <input class="form-control" name="default_value" value="<?=$model->default_value?>" placeholder="非必填">
    </div>

   <div class="input-group">
        <label>备注说明</label>
        <textarea name="remark" class="form-control" placeholder="备注说明"><?=$model->remark?></textarea>
    </div>
    <?php else:?>
    
    <div class="input-group">
        <label>参数别名</label>
        <input name="name" type="text" class="form-control" placeholder="参数别名" value="<?=$model->name?>">
    </div>
    <div class="input-group">
        <label>参数含义</label>
        <input name="title" type="text" class="form-control" placeholder="参数含义" value="<?=$model->title?>">
    </div>
    <div class="input-group">
        <label>参数类型</label>
        <select class="form-control" name="type">
            <?php foreach(FieldModel::$type_list as $key => $item):?>
               <option value="<?=$key?>" <?= $key == $model->type ? 'selected' : '' ?>><?=$item?></option>
            <?php endforeach;?>
        </select>
    </div>

    <div class="input-group">
        <label>默认值</label>
        <input class="form-control" name="default_value" value="<?=$model->default_value?>" placeholder="非必填">
    </div>

    <div class="input-group">
        <label>MOCK规则
            <a target="_blank" href="https://github.com/gouguoyin/phprap/wiki/Mock" data-toggle="tooltip" data-placement="right" title="" class="btn-show-tips" data-original-title="点击查看MOCK语法">
                <i class="fa fa-info-circle"></i>
            </a>
        </label>
        <input class="form-control" name="mock" value="<?=$model->mock?>" placeholder="非必填，如果要使用mock服务，必须填写" datatype="*" ignore="ignore">
    </div>

   <div class="input-group">
        <label>备注说明</label>
        <textarea name="remark" class="form-control" placeholder="备注说明"><?=$model->remark?></textarea>
    </div>
    <?php endif;?>


    <input type="hidden" name="id" value="<?=$model->id?>">
    <input type="hidden" name="parent_id" value="<?=$model->parent_id?>">
    <input type="hidden" name="api_id" value="<?=$model->api_id?>">
    <input type="hidden" name="kind" value="<?=$model->kind?>">
</form>