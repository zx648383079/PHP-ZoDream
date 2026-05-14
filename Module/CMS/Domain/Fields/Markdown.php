<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Infrastructure\HtmlExpand;
use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;


class Markdown extends BaseField {

    public function options(bool $isJson = false): array|string {
        $option = static::filterData(static::fieldSetting($this->field, 'option'), [
            'width' => '',
            'height' => '',
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
            ];
        }
        return implode('', [
            Theme::text('setting[option][width]', $option['width'], '宽度'),
            Theme::text('setting[option][height]', $option['height'], '高度'),
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
                'type' => 'markdown',
                'value' => $value
            ];
        }
        return (string)Theme::textarea($this->controlName(), $value, $this->controlLabel(), '',
            $this->isRequired());
    }

    public function toText(mixed $value): string {
        return HtmlExpand::toHtml((string)$value, true);
    }
}