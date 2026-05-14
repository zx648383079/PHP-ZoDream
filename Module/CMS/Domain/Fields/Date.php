<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;

use Zodream\Template\View;

class Date extends BaseField {

    public function options(bool $isJson = false): array|string {
        $option = static::filterData(static::fieldSetting($this->field, 'option'), [
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



    public function alterColumn(Column $column): void {
        $column->string(30)->default('')->comment($this->controlLabel());
    }

    public function toInput(mixed $value, bool $isJson = false): array|string {
        if ($isJson) {
            return [
                'name' => $this->controlName(),
                'label' => $this->controlLabel(),
                'type' => 'date',
                'value' => $value
            ];
        }
        $format = static::fieldSetting($this->field,'option', 'format');
        $js = <<<JS
$('[name={$this->controlName()}]').datetimer({
    format: '{$format}'
});
JS;
        view()->registerCssFile('@datetimer.min.css')
            ->registerJsFile('@jquery.datetimer.min.js')
            ->registerJs($js, View::JQUERY_READY);
        return (string)Theme::text($this->controlName(), $value, $this->controlLabel());
    }
}