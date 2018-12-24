<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
use Zodream\Html\Dark\Theme;
/** @var $this View */
$this->title = '基本设置';
$js = <<<JS
$('.zd-tab-head .zd-tab-item').eq(0).trigger('click');
JS;
$this->registerJs($js, View::JQUERY_READY);
?>
<?=Form::open('./admin/setting/save')?>
    <div class="zd-tab">
        <div class="zd-tab-head">
            <?php foreach($group_list as $group):?>
            <div class="zd-tab-item">
                <?=$group['name']?>
            </div>
            <?php endforeach;?>
            <div class="zd-tab-item">
                新增设置
            </div>
        </div>
        <div class="zd-tab-body">
            <?php foreach($group_list as $group):?>
            <div class="zd-tab-item">
                <?php foreach($group['children'] as $item):?>
                   
                <?php endforeach;?>
            </div>
            <?php endforeach;?>
            <div class="zd-tab-item">
                <?=Theme::text('field[name]', '', '名称', true)?>
                <?=Theme::text('field[code]', '', '别名', true)?>
                <?=Theme::select('field[type]', ['group' => '分组', 'text' => '文本', 'select' => '下拉选择', 'radio' => '单选', 'checkbox' => '多选', 'bool' => '开关', 'image' => '图片', 'file' => '文件'], 'group', '类型')?>
                <?=Theme::textarea('field[default_value]', '', '默认值')?>
                <?=Theme::text('field[position]', '', '排序')?>
            </div>
        </div>
    </div>
    <button type="button" class="btn btn-success btn-save">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close() ?>