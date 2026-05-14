<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;

class Email extends BaseField {

    public function options(bool $isJson = false): array|string {
        $option = static::filterData(static::fieldSetting($this->field, 'option'), [
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



    public function alterColumn(Column $column): void {
        $column->string(100)->default('')->comment($this->controlLabel());
    }

    public function toInput(mixed $value, bool $isJson = false): array|string {
        if ($isJson) {
            return [
                'name' => $this->controlName(),
                'label' => $this->controlLabel(),
                'type' => 'email',
                'value' => $value
            ];
        }
        return (string)Theme::email($this->controlName(), $value, $this->controlLabel());
    }
}