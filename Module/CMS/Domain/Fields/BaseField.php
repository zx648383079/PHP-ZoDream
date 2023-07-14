<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Contracts\Column;
use Zodream\Helpers\Html;
use Zodream\Helpers\Json;
use Zodream\Infrastructure\Support\MessageBag;
use Zodream\Database\Model\Model as BaseModel;

abstract class BaseField {

    abstract public function converterField(Column $column, ModelFieldModel $field): void;

    abstract public function options(ModelFieldModel $field, bool $isJson = false): string|array;

    abstract public function toInput(mixed $value, ModelFieldModel|array $field, bool $isJson = false): array|string;

    public function filterInput(mixed $value, ModelFieldModel|array $field, MessageBag $bag): mixed {
        if ($field['is_required'] && is_null($value)) {
            $bag->add($field['field'], sprintf('[%s]%s', $field['name'],
                !empty($field['error_message']) ? $field['error_message'] : '必填项'));
        }
        if (!is_null($value) && $field['match'] && !preg_match($field['match'], (string)$value, $_)) {
            $bag->add($field['field'],
            sprintf('[%s]%s', $field['name'], !empty($field['error_message']) ? $field['error_message'] : '必填项'));
        }
        return $value.'';
    }

    public function toText(mixed $value, ModelFieldModel $field): string {
        return Html::text((string)$value);
    }

    public static function fieldSetting(BaseModel|array $field, string ...$keys): mixed {
        if ($field instanceof BaseModel && method_exists($field, 'setting')) {
            return $field->setting(...$keys);
        }
        $data = empty($field['setting']) ? [] : (is_array($field['setting']) ? $field['setting'] : Json::decode($field['setting']));
        if (empty($data)) {
            return null;
        }
        foreach ($keys as $key) {
            if (empty($key)) {
                return $data;
            }
            if (empty($data) || !is_array($data)) {
                return null;
            }
            if (isset($data[$key]) || array_key_exists($key, $data)) {
                $data = $data[$key];
                continue;
            }
            return null;
        }
        return $data;
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

    /**
     * 多选值合并成字符串
     * @param array|string $items
     * @return string
     */
    public static function toMultipleValue(array|string $items): string {
        $items = static::fromMultipleValue($items);
        return sprintf(',%s,', implode(',', $items));
    }

    /**
     * 多选值合并成查询值
     * @param mixed $value
     * @return string
     */
    public static function toMultipleValueQuery(mixed $value): string {
        return sprintf(',%d,', $value);
    }

    /**
     * 多选的值转成数组
     * @param mixed $val
     * @return array
     */
    public static function fromMultipleValue(mixed $val): array {
        if (empty($val)) {
            return [];
        }
        $items = is_array($val) ? $val : explode(',', (string)$val);
        $data = [];
        foreach ($items as $item) {
            $item = intval($item);
            if ($item < 1 || in_array($item, $data)) {
                continue;
            }
            $data[] = $item;
        }
        return $data;
    }

}