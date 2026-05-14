<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;

class Editor extends BaseField {

    public function options(bool $isJson = false): array|string {
        $option = static::filterData(static::fieldSetting($this->field, 'option'), [
            'width' => '',
            'height' => '',
            'is_mb_auto' => 1,
            'editor_mode' => 0
        ]);
        if ($isJson) {
            return [
                [
                    'name' => 'width',
                    'label' => '宽度',
                    'type' => 'text',
                    'value' => $option['width'],
                ],
                [
                    'name' => 'height',
                    'label' => '高度',
                    'type' => 'text',
                    'value' => $option['height'],
                ],
                [
                    'name' => 'is_mb_auto',
                    'label' => '移动端自动宽度',
                    'type' => 'switch',
                    'value' => $option['is_mb_auto'],
                ],
                [
                    'name' => 'editor_mode',
                    'label' => '编辑器模式',
                    'type' => 'radio',
                    'value' => $option['editor_mode'],
                    'items' => ['完整版', '精简版', '优化版']
                ],
            ];
        }
        return implode('', [
            Theme::text('setting[option][width]', $option['width'], '宽度'),
            Theme::text('setting[option][height]', $option['height'], '高度'),
            Theme::radio('setting[option][is_mb_auto]', ['是', '否'], $option['is_mb_auto'], '移动端自动宽度'),
            Theme::radio('setting[option][editor_mode]', ['完整版', '精简版', '优化版'], $option['editor_mode'], '编辑器模式'),
        ]);
    }


    public function alterColumn(Column $column): void {
        $column->mediumtext()->nullable()->comment($this->controlLabel());
    }

    public function toInput(mixed $value, bool $isJson = false): array|string {
        if ($isJson) {
            return [
                'name' => $this->controlName(),
                'label' => $this->controlLabel(),
                'type' => 'editor',
                'value' => $value
            ];
        }
        $option = static::filterData(static::fieldSetting($this->field, 'option'), [
            'width' => '',
            'height' => '',
            'is_mb_auto' => 1,
            'editor_mode' => 0
        ]);
        $editor = \Infrastructure\Editor::html($this->controlName(), $value, $option);
        return <<<HTML
<div for="{$this->controlName()}">{$this->controlLabel()}</div>
{$editor}
HTML;
    }

    public function toText(mixed $value): string {
        return (string)$value;
    }
}