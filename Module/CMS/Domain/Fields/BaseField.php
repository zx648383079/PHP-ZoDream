<?php
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Contracts\Column;

abstract class BaseField {

    abstract public function converterField(Column $column, ModelFieldModel $field);

    abstract public function options(ModelFieldModel $field);

    abstract public function toInput($value, ModelFieldModel $field);

    public function filterInput($value, ModelFieldModel $field) {
        return $value.'';
    }

    public function toText($value, ModelFieldModel $field) {
        return $value;
    }

    public static function textToItems($text) {
        if (empty($text)) {
            return [];
        }
        $data = [];
        $i = -1;
        foreach (explode("\n", $text) as $item) {
            $i ++;
            if (empty($item)) {
                continue;
            }
            $args = explode('=>', $item, 2);
            if (count($args) === 1) {
                $data[$item] = $item;
                continue;
            }
            if ($args[0] === '') {
                $data[$i] = $item;
                continue;
            }
            $data[$args[0]] = $data[$args[1]];
        }
        return $data;
    }

}