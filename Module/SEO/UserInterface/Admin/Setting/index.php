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
    foreach (explode("\n", $value) as $i => $item) {
        if (strpos($item, ':') > 0) {
            list($key, $item) = explode(':', $item, 2);
            $data[$key] = $item;
            continue;
        }
        $data[$i] = $item;
    }
    return $data;
}

$type_list = ['group' => '分组', 'text' => '文本', 'textarea' => '多行文本', 'select' => '下拉选择', 'radio' => '单选', 'checkbox' => '多选', 'switch' => '开关', 'image' => '图片', 'file' => '文件', 'basic_editor' => '迷你编辑器', 'editor' => '编辑器', 'json' => 'JSON', 'hide' => '隐藏'];


$this->title = '基本设置';
$jsFile = $this->getAssetUri('@seo.min.js');
$js = <<<JS
$.getScript('{$jsFile}', function() {
    bindSetting();
});
JS;
$this->registerCssFile([
    '@seo.min.css'
])->registerJs($js, View::JQUERY_READY);
?>
<?=Form::open('./@admin/setting/save')?>
    <div class="tab-box option-box">
        <div class="tab-header">
            <?php foreach($group_list as $group):?>
            <div class="tab-item">
                <?=$group['name']?>
            </div>
            <?php endforeach;?>
            <div class="tab-item">
                新增设置
            </div>
        </div>
        <div class="tab-body">
            <?php foreach($group_list as $group):?>
            <div class="tab-item">
                <?php foreach($group['children'] as $item):?>
                    <?php $item['label'] = '<i class="fa fa-edit" data-id="'. $item['id'].'"></i>'.$item['name'];
                    if($item['type'] == 'text'):?>
                   <?=Theme::text(sprintf('option[%s]', $item['id']), $item['value'], $item['name'])->label($item['label'])?>
                   <?php elseif ($item['type'] == 'textarea'):?>
                   <?=Theme::textarea(sprintf('option[%s]', $item['id']), $item['value'], $item['name'])->label($item['label'])?>
                   <?php elseif ($item['type'] == 'select'):?>
                   <?=Theme::select(sprintf('option[%s]', $item['id']), parseArr($item['default_value']), $item['value'], $item['name'])->label($item['label'])?>
                   <?php elseif ($item['type'] == 'radio'):?>
                   <?=Theme::radio(sprintf('option[%s]', $item['id']), parseArr($item['default_value']), $item['value'], $item['name'])->label($item['label'])?>
                   <?php elseif ($item['type'] == 'checkbox'):?>
                   <?=Theme::checkbox(sprintf('option[%s]', $item['id']), parseArr($item['default_value']), $item['value'], $item['name'])->label($item['label'])?>
                   <?php elseif ($item['type'] == 'switch'):?>
                   <?=Theme::checkbox(sprintf('option[%s]', $item['id']), $item['default_value'], $item['value'], $item['name'])->label($item['label'])->setType('switch')?>
                   <?php elseif ($item['type'] == 'file' || $item['type'] == 'image'):?>
                   <?=Theme::file(sprintf('option[%s]', $item['id']), $item['value'], $item['name'])->label($item['label'])?>
                   <?php elseif ($item['type'] == 'hide'):?>
                   <?php else:?>
                   <?=Theme::text(sprintf('option[%s]', $item['id']), $item['value'], $item['name'])->label($item['label'])?>
                   <?php endif;?>
                <?php endforeach;?>
            </div>
            <?php endforeach;?>
            <div class="tab-item">
                <?=Theme::text('field[name]', '', '显示名称(必填)')?>
                <?=Theme::select('field[type]', $type_list, 'group', '类型')?>
                <div class="group-property">
                    <?=Theme::select('field[parent_id]', [$group_list], '', '分组')?>
                    <?=Theme::text('field[code]', '', '调用别名(必填)')?>
                    <?=Theme::checkbox('field[visibility]', ['不可见', '可见'], 1, '公开')?>
                    <?=Theme::textarea('field[default_value]', '', '默认值')?>
                </div>
                <?=Theme::text('field[position]', '99', '排序')?>
            </div>
        </div>
    </div>
    <div class="btn-group">
        <button type="submit" class="btn btn-success btn-save">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
        <a href="javascript:;" class="btn btn-info" data-action="edit">切换编辑模式</a>
    </div>
<?= Form::close() ?>

<div class="dialog dialog-box option-dialog" data-type="dialog">
    <div class="dialog-header">
        <div class="dialog-title">编辑选择项</div><i class="fa fa-close dialog-close"></i>
    </div>
    <div class="dialog-body">
    <?=Form::open('./@admin/setting/update')?>
        <?=Theme::text('name', '', '名称')?>
        <?=Theme::select('type', $type_list, 'group', '类型')?>
        <div class="group-property">
            <?=Theme::select('parent_id', [$group_list], '', '分组')?>
            <?=Theme::text('code', '', '别名')?>
            <?=Theme::checkbox('visibility', ['不可见', '可见'], 1, '公开')?>
            <?=Theme::textarea('default_value', '', '默认值')?>
        </div>
        <?=Theme::text('position', '99', '排序')?>
        <input type="hidden" name="id">
        <?= Form::close() ?>
    </div>
    <div class="dialog-footer">
        <button type="button" class="dialog-yes">确认</button>
        <button type="button" class="dialog-del">删除</button>
    </div>
</div>