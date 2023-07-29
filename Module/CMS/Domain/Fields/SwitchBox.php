<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;
use Zodream\Infrastructure\Support\MessageBag;

class SwitchBox extends BaseField {

    public function options(ModelFieldModel $field, bool $isJson = false): array|string {
        $option = static::filterData(static::fieldSetting($field, 'option'), [
            'value' => 0
        ]);
        if ($isJson) {
            return [
                [
                    'name' => 'value',
                    'label' => '默认值',
                    'type' => 'switch',
                    'value' => $option['value']
                ],
            ];
        }
        return implode('', [
            Theme::checkbox('setting[option][value]', null, $option['value'], '默认值'),
        ]);
    }



    public function converterField(Column $column, ModelFieldModel $field): void {
        $column->bool()->default(0)->comment($field->name);
    }

    public function toInput($value, ModelFieldModel|array $field, bool $isJson = false): string|array {
        if ($isJson) {
            return [
                'name' => $field['field'],
                'label' => $field['name'],
                'type' => 'switch',
                'value' => $value,
            ];
        }
        return (string)Theme::checkbox($field['field'], null, $value, $field['name']);
    }

    public function filterInput(mixed $value, ModelFieldModel|array $field, MessageBag $bag): mixed {
        return intval($value) > 0 ? 1 : 0;
    }
}