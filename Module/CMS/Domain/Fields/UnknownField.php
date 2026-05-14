<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Zodream\Database\Contracts\Column;
use Zodream\Infrastructure\Support\MessageBag;
use Zodream\Helpers\Html;

final class UnknownField implements FieldControlInterface {
    /**
     * 生成数据表列项
     */
    public function converterField(Column $column): void {
    }
    /**
     * 显示配置信息
     */
    public function options(bool $isJson = false): string|array {
        return '';
    }

    /**
     * 显示表单项
     */
    public function toInput(mixed $value, bool $isJson = false): array|string {
        return '';
    }

    /**
     * 对提交的表单值进行转换，方便保存
     */
    public function filterInput(mixed $value, MessageBag $bag): mixed {
        return '';
    }

    /**
     * 直接给模板用的，不用转换等
     */
    public function toText(mixed $value): string {
        return Html::text((string)$value);
    }

    /**
     * 格式化值给模板用的
     */
    public function formatValue(mixed $value): mixed {
        return $value;
    }
}