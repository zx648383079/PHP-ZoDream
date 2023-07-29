<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;

class Ip extends BaseField {

    public function options(ModelFieldModel $field, bool $isJson = false): array|string {
        $option = static::filterData(static::fieldSetting($field, 'option'), [
            'unique' => 0,
        ]);
        if ($isJson) {
            return [
                [
                    'name' => 'unique',
                    'label' => '验证重复',
                    'type' => 'switch',
                    'value' => $option['unique'],
                ],
            ];
        }
        return implode('', [
            Theme::checkbox('setting[option][unique]', null, $option['unique'], '验证重复'),
        ]);
    }



    public function converterField(Column $column, ModelFieldModel $field): void {
        $column->string(120)->default('')->comment($field->name);
    }

    public function toInput($value, ModelFieldModel|array $field, bool $isJson = false): array|string {
        if ($isJson) {
            return [
                'name' => $field['field'],
                'label' => $field['name'],
                'type' => 'ip',
                'value' => $value
            ];
        }
        return (string)Theme::text($field['field'], $value, $field['name']);
    }
}