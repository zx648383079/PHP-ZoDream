<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;

class Checkbox extends BaseField {

    public function options(bool $isJson = false): string|array {
        $value = static::fieldSetting($this->field, 'option', 'items');
        if ($isJson) {
            return [
                [
                    'name' => 'items',
                    'label' => '选项',
                    'type' => 'textarea',
                    'value' => $value
                ],
            ];
        }
        return implode('', [
            Theme::textarea('setting[option][items]', $value, '选项'),
        ]);
    }



    public function alterColumn(Column $column): void {
        $column->string()->default('')->comment($this->controlLabel());
    }

    public function toInput(mixed $value, bool $isJson = false): string|array {
        $options = static::fieldSetting($this->field, 'option', 'items');
        if ($isJson) {
            return [
                'name' => $this->controlName(),
                'label' => $this->controlLabel(),
                'type' => 'checkbox',
                'items' => self::textToItems($options),
                'value' => $value
            ];
        }
        return (string)Theme::checkbox($this->controlName(), self::textToItems($options), $value, $this->controlLabel());
    }

    public function toText(mixed $value): string {
        $items = self::textToItems(static::fieldSetting($this->field, 'option', 'items'));
        return array_key_exists($value, $items) ? (string)$items[$value] : '';
    }
}