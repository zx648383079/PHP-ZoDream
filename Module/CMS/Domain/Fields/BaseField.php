<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Contracts\Column;
use Zodream\Helpers\Html;
use Zodream\Infrastructure\Support\MessageBag;

abstract class BaseField {

    abstract public function converterField(Column $column, ModelFieldModel $field): void;

    abstract public function options(ModelFieldModel $field, bool $isJson = false): string|array;

    abstract public function toInput(mixed $value, ModelFieldModel $field, bool $isJson = false): array|string;

    public function filterInput(mixed $value, ModelFieldModel $field, MessageBag $bag): mixed {
        if ($field->is_required && is_null($value)) {
            $bag->add($field->field, $field->error_message ?? '必填项');
        }
        if (!is_null($value) && $field->match && !preg_match($field->match, (string)$value, $_)) {
            $bag->add($field->field, $field->error_message ?? '必填项');
        }
        return $value.'';
    }

    public function toText(mixed $value, ModelFieldModel $field): string {
        return Html::text((string)$value);
    }

    public static function textToItems(?string $text): array {
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