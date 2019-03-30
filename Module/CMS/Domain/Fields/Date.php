<?php
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Schema\Column;
use Zodream\Html\Dark\Theme;
use Zodream\Service\Factory;
use Zodream\Template\View;

class Date extends BaseField {

    public function options(ModelFieldModel $field) {
        return implode('', [
            Theme::radio('setting[option][has_time]', ['是', '否'], 1, '是否选择时间'),
            Theme::text('setting[option][format]', 'yyyy-mm-dd', '格式化'),
        ]);
    }



    public function converterField(Column $column, ModelFieldModel $field) {
        $column->varchar(100)->comment($field->name);
    }

    public function toInput($value, ModelFieldModel $field) {
        $format = $field->setting('option', 'format');
        $js = <<<JS
$('[name={$field->field}]').datetimer({
    format: '{$format}'
});
JS;
        Factory::view()->registerCssFile('@datetimer.css')
            ->registerJsFile('@jquery.datetimer.min.js')
            ->registerJs($js, View::JQUERY_READY);
        return Theme::text($field->field, $value, $field->name);
    }
}