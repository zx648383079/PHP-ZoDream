<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Contexts\SiteContextInterface;
use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Contracts\Column;
use Zodream\Helpers\Html;
use Zodream\Helpers\Json;
use Zodream\Helpers\Str;
use Zodream\Infrastructure\Support\MessageBag;
use Zodream\Database\Model\Model as BaseModel;

abstract class BaseField implements FieldControlInterface {

    public function __construct(
        protected readonly SiteContextInterface $context,
        protected readonly ModelFieldModel $field,
    ) {
    }

    /**
     * 字段的名
     */
    public function controlName(): string {
        return $this->field->field;
    }

    /**
     * 字段的显示名
     */
    public function controlLabel(): string {
        return $this->field->name;
    }

    public function isRequired(): bool {
        return $this->field->is_required > 0;
    }

    public function isDisabled(): bool {
        return $this->field->is_disable > 0;
    }

    abstract public function alterColumn(Column $column): void;

    abstract public function options(bool $isJson = false): string|array;

    abstract public function toInput(mixed $value, bool $isJson = false): array|string;

    public function filterInput(mixed $value, MessageBag $bag): mixed {
        if ($this->isRequired() && is_null($value)) {
            $bag->add($this->controlName(), sprintf('[%s]%s', $this->controlLabel(),
                !empty($field['error_message']) ? $field['error_message'] : '必填项'));
        }
        if (!is_null($value) && $this->field['match'] && !preg_match($this->field['match'], (string)$value, $_)) {
            $bag->add($this->controlName(),
            sprintf('[%s]%s', $this->controlLabel(), !empty($field['error_message']) ? $field['error_message'] : '必填项'));
        }
        return $value.'';
    }

    public function toText(mixed $value): string {
        return Html::text((string)$value);
    }

    public function formatValue(mixed $value): mixed {
        return $value;
    }

    public function inputTooltip(string $message, bool $isJson = false): array|string {
        if ($isJson) {
            return [
                'name' => $this->controlName(),
                'label' => $this->controlLabel(),
                'type' => 'error',
                'value' => $message
            ];
        }
        return <<<HTML
<div class="input-group is-invalid">
    <label for="{$this->controlName()}">{$this->controlLabel()}</label>
     <div class="invalid-tooltip">
        [{$message}]
    </div>
</div>
HTML;
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

    /**
     * 格式化数据
     * @param mixed $data
     * @param array $def
     * @return array
     */
    public static function filterData(mixed $data, array $def): array {
        if (empty($data) || !is_array($data)) {
            return $def;
        }
        $res = [];
        foreach ($def as $k => $v) {
            if (is_bool($k)) {
                $res[$k] = isset($data[$k]) ? Str::toBool($data[$k]) : $v;
                continue;
            }
            if (is_int($v)) {
                $res[$k] = isset($data[$k]) ? intval($data[$k]) : $v;
                continue;
            }
            $res[$k] = !empty($data[$k]) ? $data[$k] : $v;
        }
        return $res;
    }

    public static function textToItems(string|null $text): array {
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
    public static function toMultipleValue(mixed $items): string {
        $items = static::fromMultipleValue($items);
        return empty($items) ? '' : sprintf(',%s,', implode(',', $items));
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

    public static function toMultipleText(mixed $items): string {
        $items = static::fromMultipleText($items);
        return empty($items) ? '' : implode(',', $items);
    }

    public static function fromMultipleText(mixed $val): array {
        if (empty($val)) {
            return [];
        }
        $items = is_array($val) ? $val : explode(',', (string)$val);
        $data = [];
        foreach ($items as $item) {
            $item = trim($item);
            if (empty($item) || in_array($item, $data)) {
                continue;
            }
            $data[] = $item;
        }
        return $data;
    }

}