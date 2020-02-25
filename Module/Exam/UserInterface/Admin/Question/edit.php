<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
use Module\Exam\Domain\Model\QuestionModel;
/** @var $this View */
$this->title = '题目';
$js = <<<JS
bindEditQuestion();
JS;
$this->registerJs($js, View::JQUERY_READY);
?>
<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/question/save')?>
    <?=Form::text('title', true)?>
    <div class="input-group">
        <label>上级</label>
        <select name="course_id">
            <?php foreach($cat_list as $item):?>
            <option value="<?=$item['id']?>" <?=$model->course_id == $item['id'] ? 'selected': '' ?>>
                <?php if($item['level'] > 0):?>
                    ￂ<?=str_repeat('ｰ', $item['level'] - 1)?>
                <?php endif;?>
                <?=$item['name']?>
            </option>
            <?php endforeach;?>
        </select>
    </div>
    <?=Form::file('image')?>
    <?=Form::select('easiness', range(1, 10))->tip('数值越大越难')?>
    <?=Form::textarea('content')?>
    <?=Form::textarea('dynamic')->tip('在题目中可以用“@1” 代替')?>
    <?=Form::textarea('analysis')?>
    <?=Form::select('type', QuestionModel::$type_list)?>
    
    <div class="option-box">
        <?php $this->extend('./option', compact('model', 'option_list'));?>
    </div>
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>