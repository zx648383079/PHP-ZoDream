<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
use Zodream\Html\Dark\Theme;
/** @var $this View */
$this->title = '签到设置';
$js = <<<JS
bindCheckIn();
JS;
$this->registerJs($js);
?>
<h1><?=$this->title?></h1>
<?=Form::open('./admin/setting/checkin')?>
    <?=Theme::text('option[checkin][basic]', $data['basic'], '基本奖励', '', true)?>
    <?=Theme::text('option[checkin][loop]', $data['loop'], '几天循环', '')?>
    <div class="input-group">
        <label for="name">特别奖励</label>
        <div class="">
            <table class="plus-table">
                <thead>
                    <tr>
                        <th>天</th>
                        <th>奖励</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['plus'] as $day => $item):?>
                    <tr>
                        <td>
                            <input type="text" name="option[checkin][day][]" value="<?=$day?>">
                        </td>
                        <td>
                            <input type="text" name="option[checkin][plus][]" value="<?=$item?>">
                        </td>
                        <td>
                            <i class="fa fa-times"></i>
                        </td>
                    </tr>
                    <?php endforeach;?>
                    <tr>
                        <td>
                            <input type="text" name="option[checkin][day][]">
                        </td>
                        <td>
                            <input type="text" name="option[checkin][plus][]">
                        </td>
                        <td>
                            <i class="fa fa-times"></i>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2">
                            <i class="fa fa-plus"></i>
                        </td>
                    </tr>
                </tfoot>
            </table>

            <div class="tip">连续签到达到多少天在基本奖励上加奖</div>
        </div>
    </div>

    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close() ?>