<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Infrastructure\HtmlExpand;
use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;


class Markdown extends BaseField {

    public function options(ModelFieldModel $field, bool $isJson = false): array|string {
        $option = static::filterData(static::fieldSetting($field, 'option'), [
            'width' => '',
            'height' => '',
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
            ];
        }
        return implode('', [
            Theme::text('setting[option][width]', $option['width'], '宽度'),
            Theme::text('setting[option][height]', $option['height'], '高度'),
        ]);
    }


    public function converterField(Column $column, ModelFieldModel $field): void {
        $column->mediumtext()->nullable()->comment($field->name);
    }

    public function toInput($value, ModelFieldModel|array $field, bool $isJson = false): array|string {
        if ($isJson) {
            return [
                'name' => $field['field'],
                'label' => $field['name'],
                'type' => 'markdown',
                'value' => $value
            ];
        }
        return (string)Theme::textarea($field['field'], $value, $field['name'], '',
            $field['is_required'] > 0);
    }

    public function toText($value, ModelFieldModel|array $field): string {
        return HtmlExpand::toHtml($value, true);
    }
}