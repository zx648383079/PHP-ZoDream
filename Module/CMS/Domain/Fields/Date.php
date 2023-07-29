<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;

use Zodream\Template\View;

class Date extends BaseField {

    public function options(ModelFieldModel $field, bool $isJson = false): array|string {
        $option = static::filterData(static::fieldSetting($field, 'option'), [
            'has_time' => 1,
            'format' => 'yyyy-mm-dd'
        ]);
        if ($isJson) {
            return [
                [
                    'name' => 'has_time',
                    'label' => '不选择时间',
                    'type' => 'switch',
                    'value' => $option['has_time'],
                ],
                [
                    'name' => 'format',
                    'label' => '格式化',
                    'type' => 'text',
                    'value' => $option['format'],
                ],
            ];
        }
        return implode('', [
            Theme::radio('setting[option][has_time]', ['是', '否'], $option['has_time'], '是否选择时间'),
            Theme::text('setting[option][format]', $option['format'], '格式化'),
        ]);
    }



    public function converterField(Column $column, ModelFieldModel $field): void {
        $column->string(30)->default('')->comment($field->name);
    }

    public function toInput($value, ModelFieldModel|array $field, bool $isJson = false): array|string {
        if ($isJson) {
            return [
                'name' => $field['field'],
                'label' => $field['name'],
                'type' => 'date',
                'value' => $value
            ];
        }
        $format = static::fieldSetting($field,'option', 'format');
        $js = <<<JS
$('[name={$field['field']}]').datetimer({
    format: '{$format}'
});
JS;
        view()->registerCssFile('@datetimer.css')
            ->registerJsFile('@jquery.datetimer.min.js')
            ->registerJs($js, View::JQUERY_READY);
        return (string)Theme::text($field['field'], $value, $field['name']);
    }
}