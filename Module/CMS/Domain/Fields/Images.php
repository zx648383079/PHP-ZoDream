<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;

class Images extends BaseField {

    public function options(ModelFieldModel $field, bool $isJson = false): array|string {
        if ($isJson) {
            return [
                [
                    'name' => 'allow',
                    'label' => '允许格式',
                    'type' => 'text',
                    'value' => Image::DEFAULT_ALLOW,
                ],
                [
                    'name' => 'length',
                    'label' => '允许单张大小',
                    'type' => 'text',
                    'value' => '2M',
                ],
                [
                    'name' => 'count',
                    'label' => '允许几张',
                    'type' => 'text',
                    'value' => '*',
                ],
            ];
        }
        return implode('', [
            Theme::text('setting[option][allow]', '*', '允许格式'),
            Theme::text('setting[option][length]', '2M', '允许单张大小'),
            Theme::text('setting[option][count]', '*', '允许几张'),
        ]);
    }



    public function converterField(Column $column, ModelFieldModel $field): void {
        $column->text()->nullable()->comment($field->name);
    }

    public function toInput($value, ModelFieldModel|array $field, bool $isJson = false): string|array {
        if ($isJson) {
            return [
                'name' => $field['field'],
                'label' => $field['name'],
                'type' => 'images',
                'value' => $value
            ];
        }
        return (string)Theme::file($field['field'], $value, $field['name'], null,
            $field['is_required'] > 0);
    }
}