<?php
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;

class Radio extends BaseField {

    public function options(ModelFieldModel $field, bool $isJson = false) {
        if ($isJson) {
            return [
                [
                    'name' => 'items',
                    'label' => '选项',
                    'type' => 'textarea',
                ],
            ];
        }
        return implode('', [
            Theme::textarea('setting[option][items]', '', '选项'),
        ]);
    }



    public function converterField(Column $column, ModelFieldModel $field) {
        $column->string()->default('')->comment($field->name);
    }

    public function toInput($value, ModelFieldModel $field, bool $isJson = false) {
        if ($isJson) {
            return [
                'name' => $field->field,
                'label' => $field->name,
                'type' => 'radio',
                'value' => $value,
                'items' => self::textToItems($field->setting('option', 'items'))
            ];
        }
        return Theme::radio($field->field, self::textToItems($field->setting('option', 'items')), $value, $field->name);
    }

    public function toText($value, ModelFieldModel $field) {
        $items = self::textToItems($field->setting('option', 'items'));
        return array_key_exists($value, $items) ? $items[$value] : null;
    }
}