<?php
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;

class Text extends BaseField {

    public function options(ModelFieldModel $field) {
        return implode('', [
            Theme::text('setting[option][width]', '', '输入框宽度'),
            Theme::radio('setting[option][is_mb_auto]', ['是', '否'], 0, '移动端自动宽度'),
            Theme::checkbox('setting[option][is_pwd]', null, 0, '密码框模式'),
            Theme::checkbox('setting[option][unique]', null, 0, '验证重复'),
            Theme::text('setting[option][value]', '', '默认值'),
            Theme::select('setting[option][type]', ['int', 'char', 'varchar'], 0, '字段类型'),
        ]);
    }



    public function converterField(Column $column, ModelFieldModel $field) {
        $option = $field->setting('option');
        $type = 'string';
        if (!empty($option) && isset($option['type'])
            && in_array($option['type'], ['int', 'char', 'varchar'])) {
            $type = $option['type'];
        }
        $column->comment($field->name)->{$type}()->default($type === 'int' ? 0 : '');
    }

    public function toInput($value, ModelFieldModel $field) {
        if ($field->setting('option', 'is_pwd')) {
            return Theme::password($field->field, $value, $field->name,
                $field->is_required > 0);
        }
        return Theme::text($field->field, $value, $field->name, null,
            $field->is_required > 0);
    }
}