<?php
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;


class Color extends BaseField {

    public function options(ModelFieldModel $field, bool $isJson = false) {
        if ($isJson) {
            return [];
        }
        return '';
    }



    public function converterField(Column $column, ModelFieldModel $field) {
        $column->string(20)->default('')->comment($field->name);
    }

    public function toInput($value, ModelFieldModel $field, bool $isJson = false) {
        if ($isJson) {
            return [
                'name' => $field->field,
                'label' => $field->name,
                'type' => 'color',
                'value' => $value
            ];
        }
        view()->registerJsFile('@jscolor.min.js');
        return Theme::text($field->field, $value, $field->name)->class('jscolor');
    }
}