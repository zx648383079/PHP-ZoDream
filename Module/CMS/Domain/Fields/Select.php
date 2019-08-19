<?php
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Schema\Column;
use Zodream\Html\Dark\Theme;

class Select extends BaseField {

    public function options(ModelFieldModel $field) {
        return implode('', [
            Theme::textarea('setting[option][items]', '', '选项'),
        ]);
    }



    public function converterField(Column $column, ModelFieldModel $field) {
        $column->varchar()->comment($field->name);
    }

    public function toInput($value, ModelFieldModel $field) {
        return Theme::select($field->field, self::textToItems($field->setting('option', 'items')), $value, $field->name);
    }

    public function toText($value, ModelFieldModel $field) {
        $items = self::textToItems($field->setting('option', 'items'));
        return array_key_exists($value, $items) ? $items[$value] : null;
    }
}