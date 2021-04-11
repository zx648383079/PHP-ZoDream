<?php
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;

class Textarea extends BaseField {

    public function options(ModelFieldModel $field, bool $isJson = false) {
        if ($isJson) {
            return [
                [
                    'name' => 'width',
                    'label' => '宽度',
                    'type' => 'text',
                    'value' => '',
                ],
                [
                    'name' => 'height',
                    'label' => '高度',
                    'type' => 'text',
                    'value' => '',
                ],
                [
                    'name' => 'is_mb_auto',
                    'label' => '移动端自动宽度',
                    'type' => 'switch',
                    'value' => '',
                ],
                [
                    'name' => 'value',
                    'label' => '默认值',
                    'type' => 'text',
                    'value' => '',
                ],
                [
                    'name' => 'type',
                    'label' => '字段类型',
                    'type' => 'text',
                    'value' => 0,
                    'items' => ['char', 'varchar', 'text']
                ],
            ];
        }
        return implode('', [
            Theme::text('setting[option][width]', '', '宽度'),
            Theme::text('setting[option][height]', '', '高度'),
            Theme::radio('setting[option][is_mb_auto]', ['是', '否'], 0, '移动端自动宽度'),
            Theme::text('setting[option][value]', '', '默认值'),
            Theme::select('setting[option][type]', ['char', 'varchar', 'text'], 0, '字段类型'),
        ]);
    }

    public function converterField(Column $column, ModelFieldModel $field) {
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

    public function toInput($value, ModelFieldModel $field, bool $isJson = false) {
        if ($isJson) {
            return [
                'name' => $field->field,
                'label' => $field->name,
                'type' => 'textarea',
                'value' => $value,
            ];
        }
        return Theme::textarea($field->field, $value, $field->name, null,
            $field->is_required > 0);
    }
}