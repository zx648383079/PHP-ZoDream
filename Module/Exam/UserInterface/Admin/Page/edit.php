<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
use Module\Exam\Domain\Model\QuestionModel;

/** @var $this View */
$this->title = '试卷';
$js = <<<JS
bindEditPage();
JS;
$this->registerJs($js, View::JQUERY_READY);
?>
<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/page/save')?>
    <?=Form::text('name', true)?>
    <?=Form::select('rule_type', ['随机选题', '固定题库'])?>
    <?=Form::text('limit_time')?>
    <?=Form::text('start_at', true)?>
    <?=Form::text('end_at', true)?>
    <div class="rule-box">
        <table>
            <thead>
                <tr>
                    <th>科目</th>
                    <?php foreach(QuestionModel::$type_list as $item):?>
                       <th><?=$item?></th>
                    <?php endforeach;?>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($model->rule_value as $course_id => $types):?>
                <tr>
                    <td>
                        <select name="rule[question][course][]">
                        <?php foreach($cat_list as $item):?>
                            <option value="<?=$item['id']?>" <?=$course_id == $item['id'] ? 'selected': '' ?>>
                                <?php if($item['level'] > 0):?>
                                    ￂ<?=str_repeat('ｰ', $item['level'] - 1)?>
                                <?php endif;?>
                                <?=$item['name']?>
                            </option>
                            <?php endforeach;?>
                        </select>
                    </td>
                    <?php foreach(QuestionModel::$type_list as $i => $item):?>
                    <td>
                        <input type="text" name="rule[question][type][<?=$i?>][]" value="<?=isset($types[$i]) ? $types[$i] : 0?>">
                    </td>
                    <?php endforeach;?>
                    <td>
                        <i class="fa fa-times"></i>
                    </td>
                </tr>
                <?php endforeach;?>
                <tr>
                    <td>
                        <select name="rule[question][course][]">
                        <?php foreach($cat_list as $item):?>
                            <option value="<?=$item['id']?>">
                                <?php if($item['level'] > 0):?>
                                    ￂ<?=str_repeat('ｰ', $item['level'] - 1)?>
                                <?php endif;?>
                                <?=$item['name']?>
                            </option>
                            <?php endforeach;?>
                        </select>
                    </td>
                    <?php foreach(QuestionModel::$type_list as $i => $item):?>
                    <td>
                        <input type="text" name="rule[question][type][<?=$i?>][]">
                    </td>
                    <?php endforeach;?>
                    <td>
                        <i class="fa fa-times"></i>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">
                        <i class="fa fa-plus"></i>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>