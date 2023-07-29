<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;

class Text extends BaseField {

    public function options(ModelFieldModel $field, bool $isJson = false): array|string {
        $option = static::filterData(static::fieldSetting($field, 'option'), [
            'width' => '',
            'is_mb_auto' => 1,
            'is_pwd' => 0,
            'unique' => 0,
            'value' => '',
            'type' => 0,
        ]);
        if ($isJson) {
            return [
                [
                    'name' => 'width',
                    'label' => '宽度',
                    'type' => 'text',
                    'value' => $option['width'],
                ],
                [
                    'name' => 'is_mb_auto',
                    'label' => '移动端自动宽度',
                    'type' => 'switch',
                    'value' => $option['is_mb_auto'],
                ],
                [
                    'name' => 'is_pwd',
                    'label' => '密码框模式',
                    'type' => 'switch',
                    'value' => $option['is_pwd'],
                ],
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
                [
                    'name' => 'type',
                    'label' => '字段类型',
                    'type' => 'text',
                    'value' => $option['type'],
                    'items' => ['int', 'char', 'varchar']
                ],
            ];
        }
        return implode('', [
            Theme::text('setting[option][width]', $option['width'], '输入框宽度'),
            Theme::radio('setting[option][is_mb_auto]', ['是', '否'], $option['is_mb_auto'], '移动端自动宽度'),
            Theme::checkbox('setting[option][is_pwd]', null, $option['is_pwd'], '密码框模式'),
            Theme::checkbox('setting[option][unique]', null, $option['unique'], '验证重复'),
            Theme::text('setting[option][value]', $option['value'], '默认值'),
            Theme::select('setting[option][type]', ['int', 'char', 'varchar'], $option['type'], '字段类型'),
        ]);
    }



    public function converterField(Column $column, ModelFieldModel $field): void {
        $option = $field->setting('option');
        $type = 'string';
        if (!empty($option) && isset($option['type'])
            && in_array($option['type'], ['int', 'char', 'varchar'])) {
            $type = $option['type'];
        }
        $column->comment($field->name)->{$type}()->default($type === 'int' ? 0 : '');
    }

    public function toInput($value, ModelFieldModel|array $field, bool $isJson = false): array|string {
        if ($isJson) {
            return [
                'name' => $field['field'],
                'label' => $field['name'],
                'type' => 'text',
                'value' => $value,
            ];
        }
        if ($field instanceof ModelFieldModel && $field->setting('option', 'is_pwd')) {
            return Theme::password($field['field'], $value, $field['name'],
                $field['is_required'] > 0);
        }
        return (string)Theme::text($field['field'], $value, $field['name'], '',
            $field['is_required'] > 0);
    }
}