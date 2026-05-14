<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;

class File extends BaseField {

    const DEFAULT_ALLOW = '';

    public function options(bool $isJson = false): string|array {
        $option = static::filterData(static::fieldSetting($this->field, 'option'), [
            'allow' => static::DEFAULT_ALLOW,
            'size' => '2M',
        ]);
        if ($isJson) {
            return [
                [
                    'name' => 'allow',
                    'label' => '允许格式',
                    'type' => 'text',
                    'value' => $option['allow'],
                ],
                [
                    'name' => 'size',
                    'label' => '允许大小',
                    'type' => 'text',
                    'value' => $option['size'],
                ],
            ];
        }
        return implode('', [
            Theme::text('setting[option][allow]', $option['allow'], '允许格式'),
            Theme::text('setting[option][size]', $option['size'], '允许大小'),
        ]);
    }



    public function alterColumn(Column $column): void {
        $len = intval($this->field->length);
        $column->string($len > 10 ? $len : 255)->default('')->comment($this->controlLabel());
    }

    public function toInput(mixed $value, bool $isJson = false): array|string {
        if ($isJson) {
            return [
                'name' => $this->controlName(),
                'label' => $this->controlLabel(),
                'type' => 'file',
                'value' => $value
            ];
        }
        $option = static::fieldSetting($this->field, 'option');
        return (string)Theme::file($this->controlName(), $value, $this->controlLabel(), '',
            $this->isRequired())->options([
            'allow' => $option && isset($option['allow']) ? $option['allow'] : self::DEFAULT_ALLOW
        ]);
    }

    public function toText(mixed $value): string {
        if (empty($value)) {
            return '';
        }
        return <<<HTML
        <a href="{$value}" download>下载</a>
HTML;
    }

    public function formatValue(mixed $value): mixed {
        return $value;
    }
}