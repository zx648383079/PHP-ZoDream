<?php
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;

class Map extends BaseField {

    public function options(ModelFieldModel $field) {
        return '';
    }



    public function converterField(Column $column, ModelFieldModel $field) {
        $column->varchar()->comment($field->name);
    }

    public function toInput($value, ModelFieldModel $field) {
        return Theme::text($field->field, $value, $field->name, null,
            $field->is_required > 0);
    }
}