<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;
use Zodream\Infrastructure\Support\MessageBag;

class SwitchBox extends BaseField {

    public function options(ModelFieldModel $field, bool $isJson = false): array|string {
        if ($isJson) {
            return [
                [
                    'name' => 'value',
                    'label' => '默认值',
                    'type' => 'switch',
                ],
            ];
        }
        return implode('', [
            Theme::checkbox('setting[option][value]', null, false, '默认值'),
        ]);
    }



    public function converterField(Column $column, ModelFieldModel $field): void {
        $column->bool()->default(0)->comment($field->name);
    }

    public function toInput($value, ModelFieldModel $field, bool $isJson = false): string|array {
        if ($isJson) {
            return [
                'name' => $field->field,
                'label' => $field->name,
                'type' => 'switch',
                'value' => $value,
            ];
        }
        return Theme::checkbox($field->field, null, $value, $field->name);
    }

    public function filterInput(mixed $value, ModelFieldModel $field, MessageBag $bag): mixed {
        return intval($value) > 0 ? 1 : 0;
    }
}