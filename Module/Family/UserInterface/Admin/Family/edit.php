<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */

$this->title = $model->id > 0 ? '编辑' : '新增'. '族人';
$js = <<<JS
bindEdit();
JS;
$this->registerJs($js);
?>

<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/family/save')?>
    <div class="zd-tab">
        <div class="zd-tab-head">
            <div class="zd-tab-item active">
                基本
            </div><div class="zd-tab-item">
                生平
            </div><div class="zd-tab-item">
                配偶
            </div>
        </div>
        <div class="zd-tab-body">
            <div class="zd-tab-item active">
                <?=Form::text('name', true)?>
                <?=Form::text('secondary_name')?>
                <?=Form::select('sex', ['其他', '女', '男'], true)?>
                <?=Form::select('clan_id', [$clan_list], true)?>

                <div class="input-group">
                    <label for="parent_id">生父</label>
                    <div class="" data-action="family">
                        <span><?=$model->father ? $model->father->name : '【请选择】'?></span>
                        <input type="hidden" name="parent_id" value="<?=$model->parent_id?>">
                    </div>
                </div>

                <div class="input-group">
                    <label for="mother_id">生母</label>
                    <div class="" data-action="family">
                        <span><?=$model->mother ? $model->mother->name : '【请选择】'?></span>
                        <input type="hidden" name="mother_id" value="<?=$model->mother_id?>">
                    </div>
                </div>
                <div class="input-group">
                    <label for="birth_at">一生</label>
                    <div class="">
                        <input type="text" id="birth_at" class="form-control " name="birth_at" value="<?=$model->birth_at?>">

                        ~

                        <input type="text" id="death_at" class="form-control " name="death_at" value="<?=$model->death_at?>">

                    </div>
                </div>
            </div>
            <div class="zd-tab-item">
                <textarea id="content-box" name="lifetime" rows="10" style="width: 100%;resize: vertical;"><?=$model->lifetime?></textarea>
            </div>
            <div class="zd-tab-item">
                <?php foreach($spouse_list as $item):?>
                <div class="spouse-item" data-id="<?=$item->id?>">
                    [妻]<?=$item->spouse->name?>(<?=$item->start_at?>~<?=$item->end_at?>) 
                    <i class="fa fa-times"></i>
                </div>
                <?php endforeach;?>
                <?php if(empty($spouse_list)):?>
                <div class="spouse-item">
                    <div class="input-group">
                        <label for="mother_id">配偶</label>
                        <div class="" data-action="family">
                            <span>【请选择】</span>
                            <input type="hidden" name="spouse[spouse_id][]" value="0">
                        </div>
                    </div>
                    <?=Form::select('spouse[relation][]', ['妻'])->label('关系')?>
                    <div class="input-group">
                        <label for="start_at">婚姻时间</label>
                        <div class="">
                            <input type="text" id="start_at" class="form-control " name="spouse[start_at][]">
                            ~
                            <input type="text" id="end_at" class="form-control " name="spouse[end_at][]">
                        </div>
                    </div>
                </div>
                <?php else:?>
                <div class="spouse-add">
                    <i class="fa fa-plus"></i>
                </div>
                <?php endif;?>
                
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>

<?php $this->extend('./dialog');?>