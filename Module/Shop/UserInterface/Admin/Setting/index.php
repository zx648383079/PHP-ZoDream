<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
use Zodream\Html\Dark\Theme;
/** @var $this View */

function parseArr($value) {
    if (empty($value)) {
        return [];
    }
    $data = [];
    foreach (explode("\n", $value) as $item) {
        if (strpos($item, ':') > 0) {
            list($key, $item) = explode(':', $item, 2);
            $data[$key] = $item;
            continue;
        }
        $data[$item] = $item;
    }
    return $data;
}


$this->title = '基本设置';
$js = <<<JS
$('.zd-tab-head .zd-tab-item').eq(0).trigger('click');
$("#field_type").change(function() {
    $('.group-property').toggle($(this).val() != 'group');
}).trigger('change');
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
                   <?php if($item['type'] == 'text'):?>
                   <?=Theme::text(sprintf('option[%s]', $item['id']), $item['value'], $item['name'])?>
                   <?php elseif ($item['type'] == 'textarea'):?>
                   <?=Theme::textarea(sprintf('option[%s]', $item['id']), $item['value'], $item['name'])?>
                   <?php elseif ($item['type'] == 'select'):?>
                   <?=Theme::select(sprintf('option[%s]', $item['id']), parseArr($item['default_value']), $item['value'], $item['name'])?>
                   <?php elseif ($item['type'] == 'radio'):?>
                   <?=Theme::radio(sprintf('option[%s]', $item['id']), parseArr($item['default_value']), $item['value'], $item['name'])?>
                   <?php elseif ($item['type'] == 'checkbox'):?>
                   <?=Theme::checkbox(sprintf('option[%s]', $item['id']), parseArr($item['default_value']), $item['value'], $item['name'])?>
                   <?php elseif ($item['type'] == 'bool'):?>
                   <?=Theme::checkbox(sprintf('option[%s]', $item['id']), $item['default_value'], $item['value'], $item['name'])?>
                   <?php elseif ($item['type'] == 'file' || $item['type'] == 'image'):?>
                   <?=Theme::file(sprintf('option[%s]', $item['id']), $item['value'], $item['name'])?>
                   <?php else:?>
                   <?=Theme::text(sprintf('option[%s]', $item['id']), $item['value'], $item['name'])?>
                   <?php endif;?>
                <?php endforeach;?>
            </div>
            <?php endforeach;?>
            <div class="zd-tab-item">
                <?=Theme::text('field[name]', '', '名称(必填)')?>
                <?=Theme::select('field[type]', ['group' => '分组', 'text' => '文本', 'select' => '下拉选择', 'radio' => '单选', 'checkbox' => '多选', 'bool' => '开关', 'image' => '图片', 'file' => '文件'], 'group', '类型')?>
                <div class="group-property">
                    <?=Theme::select('field[parent_id]', [$group_list], '', '分组')?>
                    <?=Theme::text('field[code]', '', '别名(必填)')?>
                    <?=Theme::checkbox('field[visibility]', ['不可见', '可见'], 1, '公开')?>
                    <?=Theme::textarea('field[default_value]', '', '默认值')?>
                </div>
                <?=Theme::text('field[position]', '99', '排序')?>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-success btn-save">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close() ?>