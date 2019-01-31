<?php
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Schema\Column;

abstract class BaseField {

    abstract public function converterField(Column $column, ModelFieldModel $field);

    abstract public function options(ModelFieldModel $field);

    abstract public function toInput($value, ModelFieldModel $field);

    public function filterInput($value, ModelFieldModel $field) {
        return $value.'';
    }

}