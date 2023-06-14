<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
use Zodream\Html\Dark\Theme;
/** @var $this View */

$this->title = '站点配置';
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

$type_list = ['text' => '文本', 'textarea' => '多行文本', 'select' => '下拉选择', 'radio' => '单选', 'checkbox' => '多选', 'switch' => '开关', 'image' => '图片', 'file' => '文件', 'basic_editor' => '迷你编辑器', 'editor' => '编辑器', 'json' => 'JSON'];
$js = <<<JS
bindEditOption();
JS;
$this->registerJs($js);
?>

<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/site/save_option')?>
    <div class="tab-box option-box">
        <div class="tab-header">
            <div class="tab-item active">配置</div>
            <div class="tab-item">新增配置</div>
        </div>
        <div class="tab-body">
            <div class="tab-item active">
            <?php foreach($model->options as $item):?>
                <?php $item['label'] = '<i class="fa fa-times" data-id="'. $item['code'].'" title="删除此配置"></i>'.$item['name'];
                if($item['type'] == 'text'):?>
                <?=Theme::text(sprintf('option[%s]', $item['code']), $item['value'], $item['name'])->setLabel($item['label'])?>
                <?php elseif ($item['type'] == 'textarea'):?>
                <?=Theme::textarea(sprintf('option[%s]', $item['code']), $item['value'], $item['name'])->setLabel($item['label'])?>
                <?php elseif ($item['type'] == 'select'):?>
                <?=Theme::select(sprintf('option[%s]', $item['code']), parseArr($item['default_value']), $item['value'], $item['name'])->setLabel($item['label'])?>
                <?php elseif ($item['type'] == 'radio'):?>
                <?=Theme::radio(sprintf('option[%s]', $item['code']), parseArr($item['default_value']), $item['value'], $item['name'])->setLabel($item['label'])?>
                <?php elseif ($item['type'] == 'checkbox'):?>
                <?=Theme::checkbox(sprintf('option[%s]', $item['code']), parseArr($item['default_value']), $item['value'], $item['name'])->setLabel($item['label'])?>
                <?php elseif ($item['type'] == 'switch'):?>
                <?=Theme::checkbox(sprintf('option[%s]', $item['code']), $item['default_value'], $item['value'], $item['name'])->setLabel($item['label'])->setType('switch')?>
                <?php elseif ($item['type'] == 'file' || $item['type'] == 'image'):?>
                <?=Theme::file(sprintf('option[%s]', $item['code']), $item['value'], $item['name'])->setLabel($item['label'])?>
                <?php elseif ($item['type'] == 'hide'):?>
                <?php else:?>
                <?=Theme::text(sprintf('option[%s]', $item['code']), $item['value'], $item['name'])->setLabel($item['label'])?>
                <?php endif;?>
            <?php endforeach;?>
            </div>
            <div class="tab-item">
                <?=Theme::text('field[name]', '', '名称(必填)')?>
                <?=Theme::select('field[type]', $type_list, 'text', '类型')?>
                <?=Theme::text('field[code]', '', '别名(必填)')?>
                <?=Theme::textarea('field[default_value]', '', '默认值')?>
                <?=Theme::textarea('field[value]', '', '值')?>
            </div>
        </div>
    </div>
        
   
    <div class="btn-group">
        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    </div>
<?=Form::close('id')?>