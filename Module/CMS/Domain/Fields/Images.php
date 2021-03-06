<?php
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;

class Images extends BaseField {

    public function options(ModelFieldModel $field) {
        return implode('', [
            Theme::text('setting[option][allow]', '*', '允许格式'),
            Theme::text('setting[option][length]', '2M', '允许单张大小'),
            Theme::text('setting[option][count]', '*', '允许几张'),
        ]);
    }



    public function converterField(Column $column, ModelFieldModel $field) {
        $column->text()->comment($field->name);
    }

    public function toInput($value, ModelFieldModel $field) {
        return Theme::file($field->field, $value, $field->name, null,
            $field->is_required > 0);
    }
}