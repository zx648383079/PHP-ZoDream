<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;

class Url extends BaseField {

    public function options(ModelFieldModel $field, bool $isJson = false): array|string {
        if ($isJson) {
            return [];
        }
        return '';
    }



    public function converterField(Column $column, ModelFieldModel $field): void {
        $column->string()->default('')->comment($field->name);
    }

    public function toInput($value, ModelFieldModel $field, bool $isJson = false): array|string {
        if ($isJson) {
            return [
                'name' => $field->field,
                'label' => $field->name,
                'type' => 'url',
                'value' => $value,
            ];
        }
        return Theme::text($field->field, $value, $field->name);
    }
}