<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;

class Email extends BaseField {

    public function options(ModelFieldModel $field, bool $isJson = false): array|string {
        $option = static::filterData(static::fieldSetting($field, 'option'), [
            'unique' => 0,
            'value' => '',
        ]);
        if ($isJson) {
            return [
                [
                    'name' => 'unique',
                    'label' => '验证重复',
                    'type' => 'switch',
                    'value' => $option['unique'],
                ],
                [
                    'name' => 'value',
                    'label' => '默认值',
                    'type' => 'text',
                    'value' => $option['value'],
                ],
            ];
        }
        return implode('', [
            Theme::checkbox('setting[option][unique]', null, $option['unique'], '验证重复'),
            Theme::text('setting[option][value]', $option['value'], '默认值'),
        ]);
    }



    public function converterField(Column $column, ModelFieldModel $field): void {
        $column->string(100)->default('')->comment($field->name);
    }

    public function toInput($value, ModelFieldModel|array $field, bool $isJson = false): array|string {
        if ($isJson) {
            return [
                'name' => $field['field'],
                'label' => $field['name'],
                'type' => 'email',
                'value' => $value
            ];
        }
        return (string)Theme::email($field['field'], $value, $field['name']);
    }
}