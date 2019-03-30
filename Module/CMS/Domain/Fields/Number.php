<?php
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Schema\Column;
use Zodream\Html\Dark\Theme;

class Number extends BaseField {

    public function options(ModelFieldModel $field) {
        return implode('', [
            Theme::text('setting[option][width]', '', '宽度'),
            Theme::radio('setting[option][is_mb_auto]', ['是', '否'], 0, '移动端自动宽度'),
            Theme::checkbox('setting[option][unique]', null, 0, '验证重复'),
            Theme::text('setting[option][value]', '', '默认值'),
            Theme::text('setting[option][length]', '', '字段长度'),
        ]);
    }



    public function converterField(Column $column, ModelFieldModel $field) {
        $column->varchar()->comment($field->name);
    }

    public function toInput($value, ModelFieldModel $field) {
        return Theme::text($field->field, $value, $field->name);
    }
}