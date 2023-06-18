<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;

class Ip extends BaseField {

    public function options(ModelFieldModel $field, bool $isJson = false): array|string {
        if ($isJson) {
            return [
                [
                    'name' => 'unique',
                    'label' => '验证重复',
                    'type' => 'switch',
                    'value' => 0,
                ],
            ];
        }
        return implode('', [
            Theme::checkbox('setting[option][unique]', null, 0, '验证重复'),
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