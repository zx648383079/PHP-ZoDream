<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;
use Zodream\Infrastructure\Support\MessageBag;

class SwitchBox extends BaseField {

    public function options(bool $isJson = false): array|string {
        $option = static::filterData(static::fieldSetting($this->field, 'option'), [
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



    public function alterColumn(Column $column): void {
        $column->bool()->default(0)->comment($this->controlLabel());
    }

    public function toInput(mixed $value, bool $isJson = false): string|array {
        if ($isJson) {
            return [
                'name' => $this->controlName(),
                'label' => $this->controlLabel(),
                'type' => 'switch',
                'value' => $value,
            ];
        }
        return (string)Theme::checkbox($this->controlName(), null, $value, $this->controlLabel());
    }

    public function filterInput(mixed $value, MessageBag $bag): mixed {
        return intval($value) > 0 ? 1 : 0;
    }
}