<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;
use Zodream\Infrastructure\Support\MessageBag;

class Images extends BaseField {

    public function options(bool $isJson = false): array|string {
        $option = static::filterData(static::fieldSetting($this->field, 'option'), [
            'allow' => Image::DEFAULT_ALLOW,
            'size' => '2M',
            'count' => '*',
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
                    'label' => '允许单张大小',
                    'type' => 'text',
                    'value' => $option['size'],
                ],
                [
                    'name' => 'count',
                    'label' => '允许几张',
                    'type' => 'text',
                    'value' => $option['count'],
                ],
            ];
        }
        return implode('', [
            Theme::text('setting[option][allow]', $option['allow'], '允许格式'),
            Theme::text('setting[option][size]', $option['size'], '允许单张大小'),
            Theme::text('setting[option][count]', $option['count'], '允许几张'),
        ]);
    }



    public function alterColumn(Column $column): void {
        $column->text()->nullable()->comment($this->controlLabel());
    }

    public function toInput(mixed $value, bool $isJson = false): string|array {
        if ($isJson) {
            return [
                'name' => $this->controlName(),
                'label' => $this->controlLabel(),
                'type' => 'images',
                'value' => $value
            ];
        }
        $items = static::fromMultipleText($value);
        $html = '';
        foreach ($items as $item) {
            $html .= <<<HTML
<div class="large-upload-item">
    <img class="item-body" src="{$item}">
    <span class="item-close">&times;</span>
    <input type="hidden" name="{$this->controlName()}[]" value="{$item}">
</div>
HTML;
        }
        return <<<HTML
<div class="input-group">
    <label for="{$this->controlName()}">{$this->controlLabel()}</label>
     <div class="multiple-upload-panel" data-type="images">
        <div class="panel-header">
            <input type="text" name="____{$this->controlName()}" placeholder="回车/ENTER 添加图片链接" class="form-control">
            <a class="btn btn-default add-item">上传</a>
        </div>
        <div class="panel-body">
            {$html}
        </div>
    </div>
</div>
HTML;
    }

    public function filterInput(mixed $value, MessageBag $bag): mixed {
        $value = static::toMultipleText($value);
        if ($this->isRequired() && empty($value)) {
            $bag->add($this->controlName(), sprintf('[%s]%s', $this->controlLabel(),
                !empty($this->field['error_message']) ? $this->field['error_message'] : '必填项'));
        }
        return $value;
    }

    public function toText(mixed $value): string {
        $items = static::fromMultipleText($value);
        $html = '';
        foreach ($items as $item) {
            $html .= <<<HTML
<img src="{$item}">
HTML;
        }
        return $html;
    }

    public function formatValue(mixed $value): mixed {
        return static::fromMultipleText($value);
    }
}