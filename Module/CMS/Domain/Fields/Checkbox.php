<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;

class Checkbox extends BaseField {

    public function options(ModelFieldModel $field, bool $isJson = false): string|array {
        if ($isJson) {
            return [
                [
                    'name' => 'items',
                    'label' => '选项',
                    'type' => 'textarea',
                ],
            ];
        }
        return implode('', [
            Theme::textarea('setting[option][items]', '', '选项'),
        ]);
    }



    public function converterField(Column $column, ModelFieldModel $field): void {
        $column->string()->default('')->comment($field->name);
    }

    public function toInput($value, ModelFieldModel|array $field, bool $isJson = false): string|array {
        $options = static::fieldSetting($field, 'option', 'items');
        if ($isJson) {
            return [
                'name' => $field['field'],
                'label' => $field['name'],
                'type' => 'checkbox',
                'items' => self::textToItems($options),
                'value' => $value
            ];
        }
        return (string)Theme::checkbox($field['field'], self::textToItems($options), $value, $field['name']);
    }

    public function toText($value, ModelFieldModel|array $field): string {
        $items = self::textToItems(static::fieldSetting($field, 'option', 'items'));
        return array_key_exists($value, $items) ? (string)$items[$value] : '';
    }
}