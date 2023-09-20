<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;

class File extends BaseField {

    const DEFAULT_ALLOW = '';

    public function options(ModelFieldModel $field, bool $isJson = false): string|array {
        $option = static::filterData(static::fieldSetting($field, 'option'), [
            'allow' => static::DEFAULT_ALLOW,
            'size' => '2M',
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
                    'label' => '允许大小',
                    'type' => 'text',
                    'value' => $option['size'],
                ],
            ];
        }
        return implode('', [
            Theme::text('setting[option][allow]', $option['allow'], '允许格式'),
            Theme::text('setting[option][size]', $option['size'], '允许大小'),
        ]);
    }



    public function converterField(Column $column, ModelFieldModel $field): void {
        $len = intval($field->length);
        $column->string($len > 10 ? $len : 255)->default('')->comment($field->name);
    }

    public function toInput($value, ModelFieldModel|array $field, bool $isJson = false): array|string {
        if ($isJson) {
            return [
                'name' => $field['field'],
                'label' => $field['name'],
                'type' => 'file',
                'value' => $value
            ];
        }
        $option = static::fieldSetting($field, 'option');
        return (string)Theme::file($field['field'], $value, $field['name'], '',
            $field['is_required'] > 0)->options([
            'allow' => $option && isset($option['allow']) ? $option['allow'] : self::DEFAULT_ALLOW
        ]);
    }
}