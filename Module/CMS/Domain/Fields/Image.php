<?php
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;

class Image extends BaseField {

    const DEFAULT_ALLOW = 'image/*';

    public function options(ModelFieldModel $field, bool $isJson = false) {
        if ($isJson) {
            return [
                [
                    'name' => 'allow',
                    'label' => '允许格式',
                    'type' => 'text',
                    'value' => self::DEFAULT_ALLOW,
                ],
                [
                    'name' => 'length',
                    'label' => '允许大小',
                    'type' => 'text',
                    'value' => '2M',
                ],
            ];
        }
        return implode('', [
            Theme::text('setting[option][allow]', self::DEFAULT_ALLOW, '允许格式'),
            Theme::text('setting[option][length]', '2M', '允许大小'),
        ]);
    }



    public function converterField(Column $column, ModelFieldModel $field) {
        $column->string($field->length > 10 ? $field->length : 255)->default('')->comment($field->name);
    }

    public function toInput($value, ModelFieldModel $field, bool $isJson = false) {
        if ($isJson) {
            return [
                'name' => $field->field,
                'label' => $field->name,
                'type' => 'image',
                'value' => $value
            ];
        }
        $option = $field->setting('option');
        return Theme::file($field->field, $value, $field->name, null,
            $field->is_required > 0)->options([
                'allow' => $option && isset($option['allow']) ? $option['allow'] : self::DEFAULT_ALLOW
        ]);
    }
}