<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;

class Textarea extends BaseField {

    public function options(ModelFieldModel $field, bool $isJson = false): array|string {
        $option = static::filterData(static::fieldSetting($field, 'option'), [
            'width' => '',
            'height' => '',
            'is_mb_auto' => 1,
            'value' => '',
            'type' => 0
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
                    'name' => 'height',
                    'label' => '高度',
                    'type' => 'text',
                    'value' => $option['height'],
                ],
                [
                    'name' => 'is_mb_auto',
                    'label' => '移动端自动宽度',
                    'type' => 'switch',
                    'value' => $option['is_mb_auto'],
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
                    'items' => ['char', 'varchar', 'text']
                ],
            ];
        }
        return implode('', [
            Theme::text('setting[option][width]', $option['width'], '宽度'),
            Theme::text('setting[option][height]', $option['height'], '高度'),
            Theme::radio('setting[option][is_mb_auto]', ['是', '否'], $option['is_mb_auto'], '移动端自动宽度'),
            Theme::text('setting[option][value]', $option['value'], '默认值'),
            Theme::select('setting[option][type]', ['char', 'varchar', 'text'],
                $option['type'], '字段类型'),
        ]);
    }

    public function converterField(Column $column, ModelFieldModel $field): void {
        $option = $field->setting('option');
        $type = 'varchar';
        if (!empty($option) && isset($option['type'])
            && in_array($option['type'], ['char', 'varchar', 'text'])) {
            $type = $option['type'];
        }
        $column->comment($field->name)->{$type}();
        if ($type === 'text') {
            $column->nullable();
        } else {
            $column->default('');
        }
    }

    public function toInput($value, ModelFieldModel|array $field, bool $isJson = false): array|string {
        if ($isJson) {
            return [
                'name' => $field['field'],
                'label' => $field['name'],
                'type' => 'textarea',
                'value' => $value,
            ];
        }
        return (string)Theme::textarea($field['field'], $value, $field['name'], '',
            $field['is_required'] > 0);
    }
}