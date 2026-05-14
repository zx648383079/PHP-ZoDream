<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;

class Ip extends BaseField {

    public function options(bool $isJson = false): array|string {
        $option = static::filterData(static::fieldSetting($this->field, 'option'), [
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



    public function alterColumn(Column $column): void {
        $column->string(120)->default('')->comment($this->controlLabel());
    }

    public function toInput(mixed $value, bool $isJson = false): array|string {
        if ($isJson) {
            return [
                'name' => $this->controlName(),
                'label' => $this->controlLabel(),
                'type' => 'ip',
                'value' => $value
            ];
        }
        return (string)Theme::text($this->controlName(), $value, $this->controlLabel());
    }
}