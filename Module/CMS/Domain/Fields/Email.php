<?php
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;

class Email extends BaseField {

    public function options(ModelFieldModel $field, bool $isJson = false) {
        if ($isJson) {
            return [
                [
                    'name' => 'unique',
                    'label' => '验证重复',
                    'type' => 'switch',
                    'value' => 0,
                ],
                [
                    'name' => 'value',
                    'label' => '默认值',
                    'type' => 'text',
                    'value' => '',
                ],
            ];
        }
        return implode('', [
            Theme::checkbox('setting[option][unique]', null, 0, '验证重复'),
            Theme::text('setting[option][value]', '', '默认值'),
        ]);
    }



    public function converterField(Column $column, ModelFieldModel $field) {
        $column->string(100)->default('')->comment($field->name);
    }

    public function toInput($value, ModelFieldModel $field, bool $isJson = false) {
        if ($isJson) {
            return [
                'name' => $field->field,
                'label' => $field->name,
                'type' => 'email',
                'value' => $value
            ];
        }
        return Theme::email($field->field, $value, $field->name);
    }
}