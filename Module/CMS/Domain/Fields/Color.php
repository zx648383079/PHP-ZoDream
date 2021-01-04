<?php
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Schema\Column;
use Zodream\Html\Dark\Theme;


class Color extends BaseField {

    public function options(ModelFieldModel $field) {
        return '';
    }



    public function converterField(Column $column, ModelFieldModel $field) {
        $column->varchar(20)->comment($field->name);
    }

    public function toInput($value, ModelFieldModel $field) {
        view()->registerJsFile('@jscolor.min.js');
        return Theme::text($field->field, $value, $field->name)->class('jscolor');
    }
}