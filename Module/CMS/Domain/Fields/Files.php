<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;

class Files extends BaseField {

    public function options(ModelFieldModel $field, bool $isJson = false): array|string {
        $option = static::filterData(static::fieldSetting($field, 'option'), [
            'allow' => File::DEFAULT_ALLOW,
            'size' => '2M',
            'count' => '*',
        ]);
        if ($isJson) {
            return [
                [
                    'name' => 'allow',
                    'label' => '允许格式',
                    'type' => 'text',
                    'value' => $option['allow'],
                ],
                [
                    'name' => 'size',
                    'label' => '允许单个大小',
                    'type' => 'text',
                    'value' => $option['size'],
                ],
                [
                    'name' => 'count',
                    'label' => '允许数量',
                    'type' => 'text',
                    'value' => $option['count'],
                ],
            ];
        }
        return implode('', [
            Theme::text('setting[option][allow]', $option['allow'], '允许格式'),
            Theme::text('setting[option][size]', $option['size'], '允许单个大小'),
            Theme::text('setting[option][count]', $option['count'], '允许数量'),
        ]);
    }



    public function converterField(Column $column, ModelFieldModel $field): void {
        $column->text()->nullable()->comment($field->name);
    }

    public function toInput($value, ModelFieldModel|array $field, bool $isJson = false): array|string {
        if ($isJson) {
            return [
                'name' => $field['field'],
                'label' => $field['name'],
                'type' => 'files',
                'value' => $value
            ];
        }
        return (string)Theme::file($field['field'], $value, $field['name'], '',
            $field['is_required'] > 0);
    }
}